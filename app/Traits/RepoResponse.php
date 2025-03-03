<?php
namespace App\Traits;

trait RepoResponse {

    public function formatResponse(bool $status, string $msg, string $redirect_to, $data = null, string $flash_type = '',$id = null) : object
    {

        if ($flash_type == '') {
            $flash_type = $status ? 'success' : 'error';
        }

        return (object) array(
            'status'         => $status,
            'msg'            => $msg,
            'description'    => $msg,
            'data'           => $data,
            'id'             => $id,
            'redirect_to'    => $redirect_to,
            'redirect_class' => $flash_type
        );
    }

    public function apiResponse(bool $status, $code, string $msg, $error = null,  $data = null, $description = null) : object
    {
        return (object) array(
            'status'         => $status,
            'code'           => $code,
            'msg'            => $msg,
            'error'          => $error,
            'data'           => $data,
            'description'    => $description,
        );
    }
}
