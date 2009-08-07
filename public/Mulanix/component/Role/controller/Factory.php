<?php

class Role_controller_Factory {
	public static function get() {
		return new Role_model_User(1);
	}
}