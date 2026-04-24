$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

$baseUrl = if ($env:G5_BASE_URL) { $env:G5_BASE_URL } else { 'http://127.0.0.1/g5' }
$hasAdminCredentials = [string]::IsNullOrWhiteSpace($env:G5_ADMIN_ID) -eq $false -and [string]::IsNullOrWhiteSpace($env:G5_ADMIN_PASSWORD) -eq $false

try {
    $response = Invoke-WebRequest -UseBasicParsing -Uri $baseUrl -MaximumRedirection 5 -TimeoutSec 10
    if (-not $response.StatusCode) {
        throw 'Missing HTTP status code.'
    }
} catch {
    Write-Output "Admin browser smoke skipped: base URL is not reachable ($baseUrl)."
    exit 0
}

$testArgs = @('playwright', 'test', 'tests/admin-browser-smoke.spec.js')
if (-not $hasAdminCredentials) {
    $testArgs += @('-g', 'unauthenticated admin export access redirects to login')
    Write-Output 'Admin browser smoke running in unauthenticated mode only.'
} else {
    Write-Output 'Admin browser smoke running with authenticated admin coverage.'
}

& npx @testArgs
if ($LASTEXITCODE -ne 0) {
    throw 'Admin browser smoke check failed.'
}
