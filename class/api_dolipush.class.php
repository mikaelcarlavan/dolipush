<?php
/* Copyright (C) 2015   Jean-François Ferry     <jfefe@aternatik.fr>
 * Copyright (C) 2016	Laurent Destailleur		<eldy@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

use Luracast\Restler\RestException;

dol_include_once("/dolipush/class/dolipush.class.php");
require_once DOL_DOCUMENT_ROOT . '/core/lib/pdf.lib.php';

/**
 * API class for dolipushs
 *
 * @access protected
 * @class  DolibarrApiAccess {@requires user,external}
 */
class DoliPushApi extends DolibarrApi
{

    /**
     * @var array $FIELDS Mandatory fields, checked when create and update object
     */
    static $FIELDS = array();

    /**
     * @var DoliPush $dolipush {@type DoliPush}
     */
    public $dolipush;

    /**
     * Constructor
     */
    public function __construct()
    {
        global $db, $conf, $langs;
        $this->db = $db;
        $this->dolipush = new DoliPush($this->db);
        $langs->setDefaultLang('fr_FR');

    }

    /**
     * List SMS
     *
     * Get a list of SMS
     *
     * @param string	       $sortfield	        Sort field
     * @param string	       $sortdolipush	    Sort SMS
     * @param int		       $limit		        Limit for list
     * @param int		       $page		        Page number
     * @param string           $sqlfilters          Other criteria to filter answers separated by a comma. Syntax example "(t.ref:like:'SO-%') and (t.date_creation:<:'20160101')"
     * @return  array                               Array of SMS objects
     *
     * @throws RestException 404 Not found
     * @throws RestException 503 Error
     */
    public function index($sortfield = "t.rowid", $sortchargement = 'ASC', $limit = 0, $page = 0, $sqlfilters = '')
    {
        global $db, $conf;

        $obj_ret = array();


        return $obj_ret;
    }


    /**
     * Webhook to receive SMS
     *
     *
     * @param array $request_data Request data
     * @return  int     ID of dolipush
     */
    public function post($request_data = null)
    {
        global $langs, $conf, $mysoc;

        $langs->load('dolipush@dolipush');

        $user = DolibarrApiAccess::$user;

        $object = new DoliPush($this->db);

        $message_id = GETPOST('message_id', 'alpha');
        $number = GETPOST('number', 'alpha');
        $text = GETPOST('text', 'alpha');
        $sim_card_number = GETPOST('sim_card_number', 'alpha');

        $object->message_id = $message_id;
        $object->number = $number;
        $object->text = $text;
        $object->sim_card_number = $sim_card_number;
        $object->type = $object::TYPE_RECEIVED;

        if ($object->create($user) < 0) {
            throw new RestException(500, $langs->transnoentities('ErrorWhileCreatingDoliPush'));
        }

        return array(
            'error' => false,
            'message' => $langs->transnoentities('DoliPushHasBeenCreated', $object->id),
            'id' => intval($object->id),
        );

    }
}
