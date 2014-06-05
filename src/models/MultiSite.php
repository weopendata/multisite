<?php

class MultiSite extends Eloquent
{

    protected $table = 'multisite';

    protected $fillable = array('driver', 'database', 'prefix', 'username', 'password', 'sitename');
}
