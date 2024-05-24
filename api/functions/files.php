<?php

function newFile($uid, $reference, $filename, $newFilename, $path, $mimeType, $size, $date){
    $file = ORM::forTable("files")->create();
    $file->uid = $uid;
    $file->reference = $reference;
    $file->filename = $filename;
    $file->new_filename = $newFilename;
    $file->path = $path;
    $file->mime_type = $mimeType;
    $file->size = $size;
    $file->date_created = $date;
    $file->save();
}

function getFileByUid($uid){
    $file = ORM::forTable("files")->where("uid", $uid)->where("status", 1)->findOne();
    return $file;
}

function getFiles(){
    $files = ORM::forTable("files")->where("status", 1)->findMany();
    return $files;
}

function getFile($reference){
    $file = ORM::forTable("files")->where("reference", $reference)->where("status", 1)->findOne();
    return $file;
}

function getFileByUsername($username){
    $file = ORM::forTable("files")->join("users", array("files.reference", "=", "users.uid"))->where("users.username", $username)->where("files.status", 1)->findOne();
    return $file;
}

function updateFileStatusByReference($reference, $status){
    $file = ORM::forTable("files")->where("reference", $reference)->findResultSet()
    ->set("status", $status);
    $file->save();
}