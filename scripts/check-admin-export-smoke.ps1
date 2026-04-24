$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

function Read-ZipEntryText {
    param(
        [Parameter(Mandatory = $true)]
        $Archive,
        [Parameter(Mandatory = $true)]
        [string] $EntryName
    )

    $entry = $Archive.GetEntry($EntryName)
    if (-not $entry) {
        throw "ZIP entry not found: $EntryName"
    }

    $stream = $entry.Open()
    try {
        $reader = New-Object System.IO.StreamReader($stream)
        try {
            return $reader.ReadToEnd()
        } finally {
            $reader.Dispose()
        }
    } finally {
        $stream.Dispose()
    }
}

function Invoke-DotNetZipSmokeCheck {
    Add-Type -AssemblyName System.IO.Compression.FileSystem

    $json = & php (Join-Path $PSScriptRoot 'check-admin-export-smoke.php') --generate-only
    if ($LASTEXITCODE -ne 0) {
        throw 'Admin export smoke fixture generation failed.'
    }

    $payload = $json | ConvertFrom-Json
    if (-not $payload.file_path) {
        throw 'Admin export smoke fixture path is missing.'
    }

    $archive = $null
    try {
        $archive = [System.IO.Compression.ZipFile]::OpenRead($payload.file_path)
        $entryNames = @{}
        foreach ($entry in $archive.Entries) {
            $entryNames[$entry.FullName] = $true
        }

        foreach ($entryName in $payload.required_entries) {
            if (-not $entryNames.ContainsKey($entryName)) {
                throw "XLSX smoke 파일 내부 엔트리가 누락되었습니다: $entryName"
            }
        }

        $workbookXml = Read-ZipEntryText -Archive $archive -EntryName 'xl/workbook.xml'
        $sheetXml = Read-ZipEntryText -Archive $archive -EntryName 'xl/worksheets/sheet1.xml'
        $stylesXml = Read-ZipEntryText -Archive $archive -EntryName 'xl/styles.xml'

        $expectedSheetName = 'name="' + [System.Security.SecurityElement]::Escape([string] $payload.expected_sheet_name) + '"'
        if (-not $workbookXml.Contains($expectedSheetName)) {
            throw 'sheet name 정규화 결과가 workbook.xml에 반영되지 않았습니다.'
        }

        if (-not $sheetXml.Contains([string] $payload.expected_dimension)) {
            throw 'sheet dimension이 예상과 다릅니다.'
        }

        foreach ($expected in $payload.expected_strings) {
            if (-not $sheetXml.Contains([string] $expected)) {
                throw "sheet xml에서 예상 문자열을 찾지 못했습니다: $expected"
            }
        }

        if (-not $stylesXml.Contains([string] $payload.expected_style_marker)) {
            throw 'styles.xml의 기본 스타일 구성이 예상과 다릅니다.'
        }
    } finally {
        if ($archive) {
            $archive.Dispose()
        }

        if ($payload -and $payload.file_path -and (Test-Path $payload.file_path)) {
            Remove-Item $payload.file_path -Force
        }
    }

    Write-Output 'Admin export smoke check passed via .NET Zip fallback.'
}

& php -r "exit(class_exists('ZipArchive') ? 0 : 2);"
if ($LASTEXITCODE -eq 2) {
    Invoke-DotNetZipSmokeCheck
    exit 0
}
if ($LASTEXITCODE -ne 0) {
    throw 'Unable to inspect ZipArchive availability.'
}

& php (Join-Path $PSScriptRoot 'check-admin-export-smoke.php')
if ($LASTEXITCODE -ne 0) {
    throw 'Admin export smoke check failed.'
}
