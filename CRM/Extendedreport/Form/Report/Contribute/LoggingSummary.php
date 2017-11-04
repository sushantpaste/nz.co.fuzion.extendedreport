<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2017                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2017
 */
class CRM_Extendedreport_Form_Report_Contribute_LoggingSummary extends CRM_Extendedreport_Form_Report_ExtendedReport {

  protected $_baseTable = 'log_civicrm_contribution';
  /**
   */
  public function __construct() {
    $this->_columns = $this->getColumns('Contribution');
    $this->_columns['civicrm_contribution']['name'] = 'log_civicrm_contribution';
    $this->_columns['civicrm_contribution']['alias'] = 'contribution';
    foreach ($this->_columns['civicrm_contribution']['fields'] as &$field) {
      if (isset($field['statistics'])) {
        unset($field['statistics']);
      }
    }

    $this->_columns['civicrm_contribution']['fields']['log_user_id'] = array(
      'no_display' => FALSE,
      'required' => TRUE,
      'title' => ts('Changed By'),
      'alter_display' => 'alterContactID',
      'type' => CRM_Utils_Type::T_INT,
    );
    $this->_columns['civicrm_contribution']['metadata']['log_user_id'] = array(
      'title' => ts('Changed By'),
      'alter_display' => 'alterContactID',
      'type' => CRM_Utils_Type::T_INT,
    );

    $this->_columns['civicrm_contribution']['fields']['log_date'] = array(
      'default' => TRUE,
      'required' => TRUE,
      'type' => CRM_Utils_Type::T_TIME,
      'title' => ts('Change Date'),
    );
    $this->_columns['civicrm_contribution']['fields']['log_conn_id'] = array(
      'no_display' => FALSE,
      'required' => TRUE,
      'title' => ts('Change Identifier'),
      'type' => CRM_Utils_Type::T_STRING,
    );

    $this->_columns['civicrm_contribution']['fields']['log_action'] = array(
      'default' => TRUE,
      'title' => ts('Action'),
      'type' => CRM_Utils_Type::T_STRING,
    );
    $this->_columns['civicrm_contribution']['filters']['log_action'] = array(
      'operatorType' => CRM_Report_Form::OP_MULTISELECT,
      'options' => array(
        'Insert' => ts('Insert'),
        'Update' => ts('Update'),
        'Delete' => ts('Delete'),
      ),
      'default' => array('Insert', 'Update', 'Delete'),
      'title' => ts('Action'),
      'type' => CRM_Utils_Type::T_STRING,
    );
    $this->_aliases[$this->_baseTable] = 'contribution';
    parent::__construct();
  }

  /**
   * @param $value
   * @param $row
   * @param $fieldname
   *
   * @return mixed
   */
  function alterContactID($value, &$row, $fieldname) {
    if (!$value) {
      return ts('System');
    }
    $display_name = CRM_Core_DAO::singleValueQuery('SELECT display_name FROM civicrm_contact WHERE id = %1', array(1=> array($value, 'Integer')));

    if (empty($display_name)) {
      $display_name = CRM_Core_DAO::singleValueQuery('SELECT display_name FROM log_civicrm_contact WHERE id = %1 AND display_name IS NOT NULL ORDER BY log_date DESC LIMIT 1', array(1=> array($value, 'Integer')));
    }
    return $display_name  ? $display_name . '(' . $value . ')' : $value;
  }


}