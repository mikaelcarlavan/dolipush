<?php
/* Copyright (C) 2004-2017 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2017 Mikael Carlavan <contact@mika-carl.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more detaile.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/dolipush/class/dolipush.class.php
 *  \ingroup    dolipush
 *  \brief      File of class to manage SMS
 */
require_once DOL_DOCUMENT_ROOT.'/core/class/commonobject.class.php';


/**
 * Class to manage products or services
 */
class DoliPush extends CommonObject
{
	/**
	 * @var string ID to identify managed object
	 */
	public $element = 'dolipush';

	/**
	 * @var string Name of table without prefix where object is stored
	 */
	public $table_element = 'dolipush';

	/**
	 * @var string Name of subtable line
	 */
	public $table_element_line = '';

	/**
	 * @var string Name of class line
	 */
	public $class_element_line = '';

	/**
	 * @var string Field name with ID of parent key if this field has a parent
	 */
	public $fk_element = 'fk_dolipush';

	/**
	 * @var string String with name of icon for commande class. Here is object_order.png
	 */
	public $picto = 'dolipush@dolipush';

	/**
	 * 0=No test on entity, 1=Test with field entity, 2=Test with link by societe
	 * @var int
	 */
	public $ismultientitymanaged = 1;
	/**
	 * {@inheritdoc}
	 */
	protected $table_ref_field = 'rowid';

	/**
     * Gestion id
     * @var int
     */
	public $id = 0;

    /**
     * Message id
     * @var string
     */
    public $message_id = null;

    /**
     * Number
     * @var string
     */
    public $number = null;

    /**
     * Text
     * @var string
     */
    public $text = null;

    /**
     * Sim card number
     * @var string
     */
    public $sim_card_number = null;

    /**
     * Sent or received
     * @var int
     */
    public $type = 0;

	/**
	 * Creation date
	 * @var int
	 */
	public $datec;

	/**
	 * Author id
	 * @var int
	 */
	public $user_author_id = 0;

	/**
	 * Timestamp
	 * @var int
	 */
	public $tms;

	/**
     * Entity
     * @var int
     */
	public $entity;

    /**
     * Sent type
     */
    const TYPE_SENT = 0;

    /**
     * Received type
     */
    const TYPE_RECEIVED = 1;
	/**
	 *  'type' if the field format ('integer', 'integer:ObjectClass:PathToClass[:AddCreateButtonOrNot[:Filter]]', 'varchar(x)', 'double(24,8)', 'real', 'price', 'text', 'html', 'date', 'datetime', 'timestamp', 'duration', 'mail', 'phone', 'url', 'password')
	 *         Note: Filter can be a string like "(t.ref:like:'SO-%') or (t.date_creation:<:'20160101') or (t.nature:is:NULL)"
	 *  'label' the translation key.
	 *  'enabled' is a condition when the field must be managed.
	 *  'position' is the sort order of field.
	 *  'notnull' is set to 1 if not null in database. Set to -1 if we must set data to null if empty ('' or 0).
	 *  'visible' says if field is visible in list (Examples: 0=Not visible, 1=Visible on list and create/update/view forms, 2=Visible on list only, 3=Visible on create/update/view form only (not list), 4=Visible on list and update/view form only (not create). 5=Visible on list and view only (not create/not update). Using a negative value means field is not shown by default on list but can be selected for viewing)
	 *  'noteditable' says if field is not editable (1 or 0)
	 *  'default' is a default value for creation (can still be overwrote by the Setup of Default Values if field is editable in creation form). Note: If default is set to '(PROV)' and field is 'ref', the default value will be set to '(PROVid)' where id is rowid when a new record is created.
	 *  'index' if we want an index in database.
	 *  'foreignkey'=>'tablename.field' if the field is a foreign key (it is recommanded to name the field fk_...).
	 *  'searchall' is 1 if we want to search in this field when making a search from the quick search button.
	 *  'isameasure' must be set to 1 if you want to have a total on list for this field. Field type must be summable like integer or double(24,8).
	 *  'css' is the CSS style to use on field. For example: 'maxwidth200'
	 *  'help' is a string visible as a tooltip on field
	 *  'showoncombobox' if value of the field must be visible into the label of the combobox that list record
	 *  'disabled' is 1 if we want to have the field locked by a 'disabled' attribute. In most cases, this is never set into the definition of $fields into class, but is set dynamically by some part of code.
	 *  'arrayofkeyval' to set list of value if type is a list of predefined values. For example: array("0"=>"Draft","1"=>"Active","-1"=>"Cancel")
	 *  'comment' is not used. You can store here any text of your choice. It is not used by application.
	 *
	 *  Note: To have value dynamic, you can set value to 0 in definition and edit the value on the fly into the constructor.
	 */

	// BEGIN MODULEBUILDER PROPERTIES
	/**
	 * @var array  Array with all fields and their property. Do not use it as a static var. It may be modified by constructor.
	 */
	public $fields = array(
		'rowid' =>array('type'=>'integer', 'label'=>'TechnicalID', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>10),
		'entity' =>array('type'=>'integer', 'label'=>'Entity', 'default'=>1, 'enabled'=>1, 'visible'=>-2, 'notnull'=>1, 'position'=>15, 'index'=>1),
        'message_id' =>array('type'=>'varchar(255)', 'label'=>'DoliPushMessageId', 'enabled'=>1, 'visible'=>1, 'notnull'=>1, 'position'=>20),
        'number' =>array('type'=>'varchar(30)', 'label'=>'DoliPushNumber', 'enabled'=>1, 'visible'=>1, 'notnull'=>1, 'position'=>25),
		'text' =>array('type'=>'text', 'label'=>'DoliPushText', 'enabled'=>1, 'visible'=>1, 'position'=>30),
		'sim_card_number' =>array('type'=>'varchar(255)', 'label'=>'DoliPushSimCardNumber', 'enabled'=>1, 'visible'=>1, 'position'=>35),
        'type' =>array('type'=>'integer', 'label'=>'DoliPushType', 'default'=>1, 'enabled'=>1, 'visible'=>-2, 'notnull'=>1, 'position'=>40, 'index'=>1),
        'datec' =>array('type'=>'datetime', 'label'=>'DateCreation', 'enabled'=>1, 'visible'=>-1, 'position'=>55),
		'user_author_id' =>array('type'=>'integer:User:user/class/user.class.php', 'label'=>'Fk user author', 'enabled'=>1, 'visible'=>-1, 'position'=>80),
		'tms' =>array('type'=>'timestamp', 'label'=>'DateModification', 'enabled'=>1, 'visible'=>-1, 'notnull'=>1, 'position'=>100)
		);
	// END MODULEBUILDER PROPERTIES

    /**
	 *  Constructor
	 *
	 *  @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		global $langs;

		$this->db = $db;
	}

	/**
	 *	Insert dolipush into database
	 *
	 *	@param	User	$user     		User making insert
	 *  @param  int		$notrigger	    0=launch triggers after, 1=disable triggers
	 * 
	 *	@return int			     		Id of gestion if OK, < 0 if KO
	 */
	function create($user, $notrigger=0)
	{
		global $conf, $langs, $mysoc;

        $error=0;

		dol_syslog(get_class($this)."::create", LOG_DEBUG);

		$this->db->begin();

		$this->datec = dol_now();
		$this->entity = $conf->entity;
		$this->user_author_id = $user->id;


        $now = dol_now();

        $sql = "INSERT INTO ".MAIN_DB_PREFIX."dolipush (";
        $sql.= " message_id";
        $sql.= " , number";
        $sql.= " , sim_card_number";
        $sql.= " , text";
        $sql.= " , type";
        $sql.= " , datec";
        $sql.= " , user_author_id";
        $sql.= " , entity";
        $sql.= " , tms";
        $sql.= ") VALUES (";
        $sql.= " ".(!empty($this->message_id) ? "'".$this->db->escape($this->message_id)."'" : "null");
        $sql.= ", ".(!empty($this->number) ? "'".$this->db->escape($this->number)."'" : "null");
        $sql.= ", ".(!empty($this->sim_card_number) ? "'".$this->db->escape($this->sim_card_number)."'" : "null");
        $sql.= ", ".(!empty($this->text) ? "'".$this->db->escape($this->text)."'" : "null");
        $sql.= ", ".(!empty($this->type) ? $this->type : "0");
        $sql.= ", ".(!empty($this->datec) ? "'".$this->db->idate($this->datec)."'" : "null");
        $sql.= ", ".(!empty($this->user_author_id) ? $this->user_author_id : "0");
        $sql.= ", ".(!empty($this->entity) ? $this->entity : "0");
        $sql.= ", '".$this->db->idate($now)."'";
        $sql.= ")";

        dol_syslog(get_class($this)."::Create", LOG_DEBUG);
        $result = $this->db->query($sql);
        if ( $result )
        {
            $id = $this->db->last_insert_id(MAIN_DB_PREFIX."dolipush");

            if ($id > 0)
            {
                $this->id				= $id;
            }
            else
            {
                $error++;
                $this->error='ErrorFailedToGetInsertedId';
            }
        }
        else
        {
            $error++;
            $this->error=$this->db->lasterror();
        }


		if (! $error)
		{
			$result = $this->insertExtraFields();
			if ($result < 0) $error++;
		}
	

		if (! $error)
		{
            if (! $notrigger)
            {
                if ($this->type == $this::TYPE_SENT) {
                    $result = $this->call_trigger('DOLISMS_SENT',$user);
                } else {
                    $result = $this->call_trigger('DOLISMS_RECEIVED',$user);
                }

                // Call trigger
                if ($result < 0) $error++;
                // End call triggers
            }
		}

		if (! $error)
		{
			$this->db->commit();
			return $this->id;
		}
		else
		{
			$this->db->rollback();
			return -$error;
		}

	}

	/**
	 *  Load a slice in memory from database
	 *
	 *  @param	int		$id      			Id of slide
	 *  @return int     					<0 if KO, 0 if not found, >0 if OK
	 */
	function fetch($id, $message_id = '')
	{
		global $langs, $conf;

		dol_syslog(get_class($this)."::fetch id=".$id);


		// Check parameters
        if (empty($id) && empty($message_id))
        {
            $this->error = 'ErrorWrongParameters';
            //dol_print_error(get_class($this)."::fetch ".$this->error);
            return -1;
        }

		$sql = "SELECT e.rowid, e.datec, e.tms, e.message_id, e.text, e.type, e.sim_card_number, e.number, e.user_author_id, e.entity ";
		$sql.= " FROM ".MAIN_DB_PREFIX."dolipush e";
        if ($id > 0) {
            $sql.= " WHERE e.rowid=".$id;
        } else {
            $sql.= " WHERE e.entity IN (".getEntity('dolipush').") AND e.message_id='".$this->db->escape($message_id)."'";
        }

		$resql = $this->db->query($sql);
		if ( $resql )
		{
			if ($this->db->num_rows($resql) > 0)
			{
				$obj = $this->db->fetch_object($resql);

				$this->id				= $obj->rowid;

				$this->user_author_id 	= $obj->user_author_id;
				$this->datec 			= $this->db->jdate($obj->datec);
				$this->tms 			    = $this->db->jdate($obj->tms);

                $this->message_id 		= $obj->message_id;
				$this->text 	        = $obj->text;
				$this->number 	        = $obj->number;
				$this->sim_card_number 	= $obj->sim_card_number;
                $this->type 		    = $obj->type;

				$this->entity			= $obj->entity;

				$this->fetch_optionals();

				$this->db->free($resql);

				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			dol_print_error($this->db);
			return -1;
		}
	}

	// phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 *	Load array lines
	 *
	 *	@return		int						<0 if KO, >0 if OK
	 */
	public function fetch_lines()
	{
		global $langs, $conf;
		// phpcs:enable
		$this->lines = array();

		return 1;
	}

	/**
	 *  Delete a gestion from database (if not used)
	 *
	 *	@param      User	$user       
	 *  @param  	int		$notrigger	    0=launch triggers after, 1=disable triggers
	 * 	@return		int					< 0 if KO, 0 = Not possible, > 0 if OK
	 */
	function delete(User $user, $notrigger=0)
	{
		global $conf, $langs;

		$error=0;

		// Clean parameters
		$id = $this->id;

		// Check parameters
		if (empty($id))
		{
			$this->error = "Object must be fetched before calling delete";
			return -1;
		}
		
		$this->db->begin();

		$sqlz = "DELETE FROM ".MAIN_DB_PREFIX."dolipush";
		$sqlz.= " WHERE rowid = ".$id;
		dol_syslog(get_class($this).'::delete', LOG_DEBUG);
		$resultz = $this->db->query($sqlz);

		if ( ! $resultz )
		{
			$error++;
			$this->errors[] = $this->db->lasterror();
		}		

		if (! $error)
		{
			if (! $notrigger)
			{
	            // Call trigger
	            $result = $this->call_trigger('DOLISMS_DELETE',$user);
	            if ($result < 0) $error++;
	            // End call triggers
			}
		}

		if (! $error)
		{
			$this->db->commit();
			return 1;
		}
		else
		{
			foreach($this->errors as $errmsg)
			{
				dol_syslog(get_class($this)."::delete ".$errmsg, LOG_ERR);
				$this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -$error;
		}

	}

     /**
     *      \brief Return next reference of confirmation not already used (or last reference)
     *      @param	   soc  		           objet company
     *      @param     mode                    'next' for next value or 'last' for last value
     *      @return    string                  free ref or last ref
     */
    function getNextNumRef($soc, $mode = 'next')
    {
        global $conf, $langs;
        $langs->load("dolipush@dolipush");
        return '';
	}

	
	/**
	 *	Charge les informations d'ordre info dans l'objet commande
	 *
	 *	@param  int		$id       Id of order
	 *	@return	void
	 */
	function info($id)
	{
		$sql = 'SELECT e.rowid, e.datec as datec, e.tms as datem,';
		$sql.= ' e.user_author_id as fk_user_author';
		$sql.= ' FROM '.MAIN_DB_PREFIX.'dolipush as e';
		$sql.= ' WHERE e.rowid = '.$id;
		$result=$this->db->query($sql);
		if ($result)
		{
			if ($this->db->num_rows($result))
			{
				$obj = $this->db->fetch_object($result);
				$this->id = $obj->rowid;
				if ($obj->fk_user_author)
				{
					$cuser = new User($this->db);
					$cuser->fetch($obj->fk_user_author);
					$this->user_creation   = $cuser;
				}

				$this->date_creation     = $this->db->jdate($obj->datec);
				$this->date_modification = $this->db->jdate($obj->datem);
			}

			$this->db->free($result);
		}
		else
		{
			dol_print_error($this->db);
		}
	}

    /**
     *	Return clicable link of object (with eventually picto)
     *
     *	@param      int			$withpicto                Add picto into link
     *	@param      int			$max          	          Max length to show
     *	@param      int			$short			          ???
     *  @param	    int   	    $notooltip		          1=Disable tooltip
     *	@return     string          			          String with URL
     */
    function getNomUrl($withpicto=0, $option='', $max=0, $short=0, $notooltip=0)
    {
        global $conf, $langs, $user;
        return '';
	}
	
    /**
     *	Return status label of DoliPush
     *
     *	@param      int		$mode       0=libelle long, 1=libelle court, 2=Picto + Libelle court, 3=Picto, 4=Picto + Libelle long, 5=Libelle court + Picto
     *	@return     string      		Libelle
     */
    function getLibStatut($mode)
    {
		return $this->LibStatut($this->fk_statut, $mode);
	}

	// phpcs:disable PEAR.NamingConventions.ValidFunctionName.ScopeNotCamelCaps
	/**
	 *	Return label of status
	 *
	 *	@param		int		$status      	  Id status
	 *	@param      int		$mode        	  0=Long label, 1=Short label, 2=Picto + Short label, 3=Picto, 4=Picto + Long label, 5=Short label + Picto, 6=Long label + Picto
	 *  @return     string					  Label of status
	 */
	public function LibStatut($status, $mode)
	{
		// phpcs:enable
		global $langs, $conf;
        return '';
    }

      /**
     * 	Create an array of order lines
     *
     * 	@return int		>0 if OK, <0 if KO
     */
    function getLinesArray()
    {
        return $this->fetch_lines();
    }

	/**
	 *  Return list of dolipushs
	 *
	 *  @return     int             		-1 if KO, array with result if OK
	 */
	function liste_array()
	{
		global $user;

		$dolipushs = array();

		$sql = "SELECT e.rowid as id, e.ref, e.datec";
		$sql.= " FROM ".MAIN_DB_PREFIX."dolipush as e";
		$sql.= " WHERE e.entity IN (".getEntity('dolipush').")";

		$result=$this->db->query($sql);
		if ($result)
		{
			$num = $this->db->num_rows($result);
			if ($num)
			{
				$i = 0;
				while ($i < $num)
				{
					$obj = $this->db->fetch_object($result);

					$datec = $this->db->jdate($obj->datec);
					$dolipush = new DoliPush($this->db);
					$dolipush->fetch($obj->id);

					$dolipushs[$obj->id] = $dolipush;

					$i++;
				}
			}
			return $dolipushs;
		}
		else
		{
			dol_print_error($this->db);
			return -1;
		}
	}

    /**
     *  Return list of dolipushs
     *
     *  @return     int             		-1 if KO, array with result if OK
     */
    function send($number = '', $text = '')
    {
        global $conf, $user, $langs;

        $url = 'https://api.octopush.com/v1/public/sms-campaign/send';

        $params = array(
            'recipients' => array(
                0 => array(
                    'phone_number' => $number,
                )
            ),
            'text' => $text,
            'type' => 'sms_premium',
            'purpose' => 'alert',
            'sender' => $conf->global->DOLIPUSH_SENDER
        );

        $result = $this->call($url, $params);

        dol_syslog("DoliPush::send '".json_encode($result));

        if ($result && isset($result->sms_ticket)) {
            $dolipush = new DoliPush($this->db);
            $dolipush->message_id = $result->sms_ticket;
            $dolipush->text = $text;
            $dolipush->number = $number;
            $dolipush->type = DoliPush::TYPE_SENT;

            $dolipush->create($user);
        }

        return $result;
    }

    public function call($url, $params = array()) {

        global $conf;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        curl_setopt($ch, CURLOPT_REFERER, DOL_URL_ROOT);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'api-key: '.$conf->global->DOLIPUSH_KEY,
            'api-login: '.$conf->global->DOLIPUSH_LOGIN,
            'Content-Type: application/json'
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result) {
            return json_decode($result);
        } else {
            return null;
        }
    }
}
