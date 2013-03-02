<?php

namespace Loculus\Entity;


class Base
{
    /**
     * Object is not active, e.g. not ready yet for public use
     * @var int
     */
    const STATUS_NOT_ACTIVE = 0;
    /**
     * Object is normally working and available
     * @var int
     */
    const STATUS_ACTIVE = 1;
    /**
     * Object is disabled and not accessible
     * @var int
     */
    const STATUS_DISABLED = 2;
    /**
     * Object is suspended e.g. by time - its lifetime passed
     * @var int
     */
    const STATUS_SUSPENDED = 3;


    const OBJECT_ALREADY_EXISTS = 'alreadyExists';


    public static $statuses = array(
        self::STATUS_NOT_ACTIVE,
        self::STATUS_ACTIVE,
        self::STATUS_DISABLED,
        self::STATUS_SUSPENDED,
    );

    /**
     * @var array
     */
    public static $messageTemplates = array(
        self::OBJECT_ALREADY_EXISTS => 'There is already %s with name `%s` and rest of details',
    );
}