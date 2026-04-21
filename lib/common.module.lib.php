<?php
if (!defined('_GNUBOARD_')) exit;

// 인증 모듈 실행 체크
function module_exec_check($exe, $type)
{
    $error = '';
    $is_linux = false;
    if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
        $is_linux = true;
    }

    if (!is_file($exe)) {
        $error = $exe.' 파일이 존재하지 않습니다.';
    } else {
        if (!is_executable($exe)) {
            if ($is_linux) {
                $error = $exe.'\n파일의 실행권한이 없습니다.\n\nchmod 755 '.basename($exe).' 과 같이 실행권한을 부여해 주십시오.';
            } else {
                $error = $exe.'\n파일의 실행권한이 없습니다.\n\n'.basename($exe).' 파일에 실행권한을 부여해 주십시오.';
            }
        } else {
            if ($is_linux) {
                if (!function_exists('exec')) {
                    alert('exec 함수실행이 불가능하므로 사용할수 없습니다.');
                }

                $search = false;
                $isbinary = true;

                switch ($type) {
                    case 'ct_cli':
                        exec($exe.' -h 2>&1', $out, $return_var);

                        if ($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for ($i = 0; $i < count($out); $i++) {
                            if (strpos($out[$i], 'KCP ENC') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                    default:
                        $search = true;
                        break;
                }

                if (!$isbinary || !$search) {
                    $error = $exe.'\n파일을 바이너리 타입으로 다시 업로드하여 주십시오.';
                }
            }
        }
    }

    if ($error) {
        $error = '<script>alert("'.$error.'");</script>';
    }

    return $error;
}
