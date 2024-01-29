<?php

namespace App\Http\Helpers;

class UploadFile
{
  public static function store($directory, $file)
  {
    $extension = $file->getClientOriginalExtension();
    $fileName = uniqid() . '.' . $extension;
    @mkdir($directory, 0775, true);
    $file->move($directory, $fileName);

    return $fileName;
  }

  public static function update($directory, $newFile, $oldFile)
  {
    @unlink($directory . $oldFile);
    $extension = $newFile->getClientOriginalExtension();
    $fileName = uniqid() . '.' . $extension;
    @mkdir($directory, 0775, true);
    $newFile->move($directory, $fileName);

    return $fileName;
  }
}
