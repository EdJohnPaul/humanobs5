<?php
function getUser() {
	$data = ORM::forTable('users')
	->findMany();
	return $data;
}
