<?php

namespace Bricks\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\LogEntry;

/**
 * Bricks\SiteBundle\Entity\BrickLogEntry
 *
 * @ORM\Table("brick_log_entry")
 * @ORM\Entity()
 */
class BrickLogEntry extends LogEntry
{
}
