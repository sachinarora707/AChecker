<?php
/************************************************************************/
/* AChecker                                                             */
/************************************************************************/
/* Copyright (c) 2008 - 2011                                            */
/* Inclusive Design Institute                                           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id$

/**
* DAO for "checks" table
* @access	public
* @author	Cindy Qi Li
* @package	DAO
*/

if (!defined('AC_INCLUDE_PATH')) exit;

require_once(AC_INCLUDE_PATH. 'classes/DAO/DAO.class.php');
require_once(AC_INCLUDE_PATH. 'classes/Utility.class.php');

class ChecksDAO extends DAO {

	/**
	* Create a new guideline
	* @access  public
	* @param   $userID : user id
	*          $html_tag, $confidence, $note, $name, $err, $desc, $long_desc, 
	*          $rationale, $how_to_repair, $repair_example, $question, $decision_pass, 
	*          $decision_fail, $test_procedure, $test_expected_result,
	*          $test_failed_result, $open_to_public
	* @return  checkID : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Create($userID, $html_tag, $confidence, 
	                       $note, $name, $err, $desc, $search_str, $long_desc, 
	                       $rationale, $how_to_repair, $repair_example,
	                       $question, $decision_pass, $decision_fail,
	                       $test_procedure, $test_expected_result, 
	                       $test_failed_result, $open_to_public)
	{
		global $addslashes;
		
		$userID = intval($userID);
		$html_tag = $addslashes(strtolower(trim($html_tag)));

		// $addslashes are not needed on the following fields since they are eventually
		// calling LanguageTextDAO->setText() where $addslashes is used.
		$note = trim($note);
		$name = trim($name);
		$err = trim($err);
		$desc = trim($desc);
		$search_str = trim($search_str);
		$long_desc = trim($long_desc);
		$rationale = trim($rationale);
		$how_to_repair = trim($how_to_repair);
		$repair_example = trim($repair_example);
		$question = trim($question);
		$decision_pass = trim($decision_pass);
		$decision_fail = trim($decision_fail);
		$test_procedure = trim($test_procedure);
		$test_expected_result = trim($test_expected_result);
		$test_failed_result = trim($test_failed_result);
		
		if (!$this->isFieldsValid($html_tag, $confidence, $name, $err, $open_to_public)) return false;
		
		$sql = "INSERT INTO ".TABLE_PREFIX."checks
				(`user_id`, `html_tag`, `confidence`, `open_to_public`, `create_date`) 
				VALUES
				(".$userID.",'".$html_tag."', '".$confidence."', ".
		           $open_to_public.", now())";

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			$checkID = mysqli_insert_id($this->db);
			
			if ($note <> '')
			{
				$term_note = LANG_PREFIX_CHECKS_NOTE.$checkID;
				$this->updateLang($checkID, $term_note, $note, 'note');
			}
			if ($name <> '')
			{
				$term_name = LANG_PREFIX_CHECKS_NAME.$checkID;
				$this->updateLang($checkID, $term_name, $name, 'name');
			}
			if ($err <> '')
			{
				$term_err = LANG_PREFIX_CHECKS_ERR.$checkID;
				$this->updateLang($checkID, $term_err, $err, 'err');
			}
			if ($desc <> '')
			{
				$term_desc = LANG_PREFIX_CHECKS_DESC.$checkID;
				$this->updateLang($checkID, $term_desc, $desc, 'description');
			}
			if ($search_str<> '')
			{
				$term_search_str = LANG_PREFIX_CHECKS_SEARCH_STR.$checkID;
				$this->updateLang($checkID, $term_search_str, $search_str, 'search_str');
			}
			if ($long_desc <> '')
			{
				$term_long_desc = LANG_PREFIX_CHECKS_LONG_DESC.$checkID;
				$this->updateLang($checkID, $term_long_desc, $long_desc, 'long_description');
			}
			if ($rationale <> '')
			{
				$term_rationale = LANG_PREFIX_CHECKS_RATIONALE.$checkID;
				$this->updateLang($checkID, $term_rationale, $rationale, 'rationale');
			}
			if ($how_to_repair <> '')
			{
				$term_how_to_repair = LANG_PREFIX_CHECKS_HOW_TO_REPAIR.$checkID;
				$this->updateLang($checkID, $term_how_to_repair, $how_to_repair, 'how_to_repair');
			}
			if ($repair_example <> '')
			{
				$term_repair_example = LANG_PREFIX_CHECKS_REPAIR_EXAMPLE.$checkID;
				$this->updateLang($checkID, $term_repair_example, $repair_example, 'repair_example');
			}
			if ($question <> '')
			{
				$term_question = LANG_PREFIX_CHECKS_QUESTION.$checkID;
				$this->updateLang($checkID, $term_question, $question, 'question');
			}
			if ($decision_pass <> '')
			{
				$term_decision_pass = LANG_PREFIX_CHECKS_DECISION_PASS.$checkID;
				$this->updateLang($checkID, $term_decision_pass, $decision_pass, 'decision_pass');
			}
			if ($decision_fail <> '')
			{
				$term_decision_fail = LANG_PREFIX_CHECKS_DECISION_FAIL.$checkID;
				$this->updateLang($checkID, $term_decision_fail, $decision_fail, 'decision_fail');
			}
			if ($test_procedure <> '')
			{
				$term_test_procedure = LANG_PREFIX_CHECKS_PROCEDURE.$checkID;
				$this->updateLang($checkID, $term_test_procedure, $test_procedure, 'test_procedure');
			}
			if ($test_expected_result <> '')
			{
				$term_test_expected_result = LANG_PREFIX_CHECKS_EXPECTED_RESULT.$checkID;
				$this->updateLang($checkID, $term_test_expected_result, $test_expected_result, 'test_expected_result');
			}
			if ($test_failed_result <> '')
			{
				$term_test_failed_result = LANG_PREFIX_CHECKS_FAILED_RESULT.$checkID;
				$this->updateLang($checkID, $term_test_failed_result, $test_failed_result, 'test_failed_result');
			}
			return $checkID;
		}
	}
	
	/**
	* Update a existing check
	* @access  public
	* @param   $checkID: check id
	*          $userID : user id
	*          $html_tag, $confidence, $note, $name, $err, $desc, $long_desc, 
	*          $rationale, $how_to_repair, $repair_example, $question, $decision_pass, 
	*          $decision_fail, $test_procedure, $test_expected_result,
	*          $test_failed_result, $open_to_public
	* @return  true : if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function Update($checkID, $userID, $html_tag, $confidence, 
	                       $note, $name, $err, $desc, $search_str, $long_desc, 
	                       $rationale, $how_to_repair, $repair_example,
	                       $question, $decision_pass, $decision_fail,
	                       $test_procedure, $test_expected_result, 
	                       $test_failed_result, $open_to_public)
	{
		global $addslashes;
		
		$userID = intval($userID);
		$html_tag = $addslashes(strtolower(trim($html_tag)));
		$confidence = intval($confidence);
		$open_to_public = intval($open_to_public);
		
		// $addslashes are not needed on the following fields since they are eventually
		// calling LanguageTextDAO->setText() where $addslashes is used.
		$note = trim($note);
		$name = trim($name);
		$err = trim($err);
		$desc = trim($desc);
		$search_str = trim($search_str);
		$long_desc = trim($long_desc);
		$rationale = trim($rationale);
		$how_to_repair = trim($how_to_repair);
		$repair_example = trim($repair_example);
		$question = trim($question);
		$decision_pass = trim($decision_pass);
		$decision_fail = trim($decision_fail);
		$test_procedure = trim($test_procedure);
		$test_expected_result = trim($test_expected_result);
		$test_failed_result = trim($test_failed_result);
		
		if (!$this->isFieldsValid($html_tag, $confidence, $name, $err, $open_to_public)) return false;
		
		$sql = "UPDATE ".TABLE_PREFIX."checks
				   SET `user_id`=".$userID.", 
				       `html_tag` = '".$html_tag."', 
				       `confidence` = '".$confidence."', 
				       `open_to_public` = ".$open_to_public." 
				 WHERE check_id = ".$checkID;

		if (!$this->execute($sql))
		{
			$msg->addError('DB_NOT_UPDATED');
			return false;
		}
		else
		{
			// find language term to update	
			$row = $this->getCheckByID($checkID);

			require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
			$langTextDAO = new LanguageTextDAO();
			
			if ($note <> '')
			{
				$term_note = LANG_PREFIX_CHECKS_NOTE.$checkID;
				$this->updateLang($checkID, $term_note, $note, 'note');
			}
			else
			{
				if ($row['note'] <> '') $this->deleteLang($checkID, $row['note'], 'note');
			}
			
			if ($name <> '')
			{
				$term_name = LANG_PREFIX_CHECKS_NAME.$checkID;
				$this->updateLang($checkID, $term_name, $name, 'name');
			}
			else
			{
				if ($row['name'] <> '') $this->deleteLang($checkID, $row['name'], 'name');
			}
			
			if ($err <> '')
			{
				$term_err = LANG_PREFIX_CHECKS_ERR.$checkID;
				$this->updateLang($checkID, $term_err, $err, 'err');
			}
			else
			{
				if ($row['err'] <> '') $this->deleteLang($checkID, $row['err'], 'err');
			}
			
			if ($desc <> '')
			{
				$term_desc = LANG_PREFIX_CHECKS_DESC.$checkID;
				$this->updateLang($checkID, $term_desc, $desc, 'description');
			}
			else
			{
				if ($row['description'] <> '') $this->deleteLang($checkID, $row['description'], 'description');
			}
			
			if ($search_str<> '')
			{
				$term_search_str = LANG_PREFIX_CHECKS_SEARCH_STR.$checkID;
				$this->updateLang($checkID, $term_search_str, $search_str, 'search_str');
			}
			else
			{
				if ($row['search_str'] <> '') $this->deleteLang($checkID, $row['search_str'], 'search_str');
			}
			
			if ($long_desc <> '')
			{
				$term_long_desc = LANG_PREFIX_CHECKS_LONG_DESC.$checkID;
				$this->updateLang($checkID, $term_long_desc, $long_desc, 'long_description');
			}
			else
			{
				if ($row['long_description'] <> '') $this->deleteLang($checkID, $row['long_description'], 'long_description');
			}
			
			if ($rationale <> '')
			{
				$term_rationale = LANG_PREFIX_CHECKS_RATIONALE.$checkID;
				$this->updateLang($checkID, $term_rationale, $rationale, 'rationale');
			}
			else
			{
				if ($row['rationale'] <> '') $this->deleteLang($checkID, $row['rationale'], 'rationale');
			}
			
			if ($how_to_repair <> '')
			{
				$term_how_to_repair = LANG_PREFIX_CHECKS_HOW_TO_REPAIR.$checkID;
				$this->updateLang($checkID, $term_how_to_repair, $how_to_repair, 'how_to_repair');
			}
			else
			{
				if ($row['how_to_repair'] <> '') $this->deleteLang($checkID, $row['how_to_repair'], 'how_to_repair');
			}
			
			if ($repair_example <> '')
			{
				$term_repair_example = LANG_PREFIX_CHECKS_REPAIR_EXAMPLE.$checkID;
				$this->updateLang($checkID, $term_repair_example, $repair_example, 'repair_example');
			}
			else
			{
				if ($row['repair_example'] <> '') $this->deleteLang($checkID, $row['repair_example'], 'repair_example');
			}
			
			if ($question <> '')
			{
				$term_question = LANG_PREFIX_CHECKS_QUESTION.$checkID;
				$this->updateLang($checkID, $term_question, $question, 'question');
			}
			else
			{
				if ($row['question'] <> '') $this->deleteLang($checkID, $row['question'], 'question');
			}
			
			if ($decision_pass <> '')
			{
				$term_decision_pass = LANG_PREFIX_CHECKS_DECISION_PASS.$checkID;
				$this->updateLang($checkID, $term_decision_pass, $decision_pass, 'decision_pass');
			}
			else
			{
				if ($row['decision_pass'] <> '') $this->deleteLang($checkID, $row['decision_pass'], 'decision_pass');
			}
			
			if ($decision_fail <> '')
			{
				$term_decision_fail = LANG_PREFIX_CHECKS_DECISION_FAIL.$checkID;
				$this->updateLang($checkID, $term_decision_fail, $decision_fail, 'decision_fail');
			}
			else
			{
				if ($row['decision_fail'] <> '') $this->deleteLang($checkID, $row['decision_fail'], 'decision_fail');
			}
			
			if ($test_procedure <> '')
			{
				$term_test_procedure = LANG_PREFIX_CHECKS_PROCEDURE.$checkID;
				$this->updateLang($checkID, $term_test_procedure, $test_procedure, 'test_procedure');
			}
			else
			{
				if ($row['test_procedure'] <> '') $this->deleteLang($checkID, $row['test_procedure'], 'test_procedure');
			}
			
			if ($test_expected_result <> '')
			{
				$term_test_expected_result = LANG_PREFIX_CHECKS_EXPECTED_RESULT.$checkID;
				$this->updateLang($checkID, $term_test_expected_result, $test_expected_result, 'test_expected_result');
			}
			else
			{
				if ($row['test_expected_result'] <> '') $this->deleteLang($checkID, $row['test_expected_result'], 'test_expected_result');
			}
			
			if ($test_failed_result <> '')
			{
				$term_test_failed_result = LANG_PREFIX_CHECKS_FAILED_RESULT.$checkID;
				$this->updateLang($checkID, $term_test_failed_result, $test_failed_result, 'test_failed_result');
			}
			else
			{
				if ($row['test_failed_result'] <> '') $this->deleteLang($checkID, $row['test_failed_result'], 'test_failed_result');
			}
		}
	}
	
	/**
	* Delete a check by check ID
	* @access  public
	* @param   $checkID
	* @return  true / false
	* @author  Cindy Qi Li
	*/
	function Delete($checkID)
	{
		$checkID = intval($checkID);
		
		// delete all languages
		require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/CheckPrerequisitesDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/TestPassDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/SubgroupChecksDAO.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/Techniques.class.php');
		require_once(AC_INCLUDE_PATH.'classes/DAO/CheckExamplesDAO.class.php');
		
		$langTextDAO = new LanguageTextDAO();

		$row = $this->getCheckByID($checkID);
		if ($row['note'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['note']);
		if ($row['name'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['name']);
		if ($row['err'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['err']);
		if ($row['description'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['description']);
		if ($row['search_str'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['search_str']);
		if ($row['long_description'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['long_description']);
		if ($row['rationale'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['rationale']);
		if ($row['how_to_repair'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['how_to_repair']);
		if ($row['repair_example'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['repair_example']);
		if ($row['question'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['question']);
		if ($row['decision_pass'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['decision_pass']);
		if ($row['decision_fail'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['decision_fail']);
		if ($row['test_procedure'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['test_procedure']);
		if ($row['test_expected_result'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['test_expected_result']);
		if ($row['test_failed_result'] <> '') $langTextDAO->DeleteByVarAndTerm('_check', $row['test_failed_result']);
		
		$checkPrerequisitesDAO = new CheckPrerequisitesDAO();
		$checkPrerequisitesDAO->DeleteByCheckID($checkID);
		
		$testPassDAO = new TestPassDAO();
		$testPassDAO->DeleteByCheckID($checkID);
		
		$subgroupChecksDAO = new SubgroupChecksDAO();
		$subgroupChecksDAO->DeleteByCheckID($checkID);
		
		$techniques = new Techniques();
		$techniques->DeleteByCheckID($checkID);
		
		$checkExamplesDAO = new CheckExamplesDAO();
		$checkExamplesDAO->DeleteByCheckID($checkID);
		
		$sql = "DELETE FROM ". TABLE_PREFIX ."checks WHERE check_id=".$checkID;
		
		return $this->execute($sql);
	}

	/**
	* Set checks.func field
	* @access  public
	* @param   $checkID: required
	*          $func
	* @return  true / false
	* @author  Cindy Qi Li
	*/
	function setFunction($checkID, $func)
	{
		global $addslashes;
		
		$sql = "UPDATE ". TABLE_PREFIX ."checks 
		           SET func = '".$addslashes($func)."' 
		         WHERE check_id=".intval($checkID);
		
		return $this->execute($sql);
	}
	
	/**
	* Return all checks' info
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAll()
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks";
		return $this->execute($sql);
	}

	/**
	* Return all html tags 
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllHtmlTags()
	{
		$sql = "SELECT distinct html_tag FROM ". TABLE_PREFIX ."checks ORDER BY html_tag";
		return $this->execute($sql);
	}

	/**
	* Return all open-to-public checks 
	* @access  public
	* @param   none
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getAllOpenChecks()
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks WHERE open_to_public=1";
		return $this->execute($sql);
	}

	/**
	* Return check info of given check id
	* @access  public
	* @param   $checkID : check id
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getCheckByID($checkID)
	{
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks WHERE check_id=". intval($checkID);
		$rows = $this->execute($sql);

	    if (is_array($rows))
	    	return $rows[0];
	    else
	    	return false;
	  }

	/**
	* Return check info of given check id
	* This function is only called by UsersDAO->Delete() for now.
	* @access  public
	* @param   $userID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getCheckByUserIDs($userIDs)
	{
		// $userIDs array has been sanitized in caller UsersDAO->Delete()
		$userIDs_str = implode(",", $userIDs);
		
		$sql = "SELECT * FROM ". TABLE_PREFIX ."checks WHERE user_id in (". $userIDs_str.")";
		return $this->execute($sql);
	}

	/**
	* Return checks for all html elements by given guideline ids
	* @access  public
	* @param   $gid : guideline ID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getChecksByGuidelineID($gid)
	{
		$sql = "select distinct c.*
						from ". TABLE_PREFIX ."guidelines g, 
								". TABLE_PREFIX ."guideline_groups gg, 
								". TABLE_PREFIX ."guideline_subgroups gs, 
								". TABLE_PREFIX ."subgroup_checks gc,
								". TABLE_PREFIX ."checks c
						where g.guideline_id = ".intval($gid)."
							and g.guideline_id = gg.guideline_id
							and gg.group_id = gs.group_id
							and gs.subgroup_id = gc.subgroup_id
							and gc.check_id = c.check_id
						order by c.html_tag";

    	return $this->execute($sql);
  	}

  /**
	* Return checks for all html elements by given guideline ids
	* @access  public
	* @param   $gids : an array of guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getOpenChecksForAllByGuidelineIDs($gids)
	{
		if (!is_array($gids)) return false;
		
		$sanitized_gids = Utility::sanitizeIntArray($gids);
		
		$sql = "select distinct gc.check_id, c.html_tag
						from ". TABLE_PREFIX ."guidelines g, 
								". TABLE_PREFIX ."guideline_groups gg, 
								". TABLE_PREFIX ."guideline_subgroups gs, 
								". TABLE_PREFIX ."subgroup_checks gc,
								". TABLE_PREFIX ."checks c
						where g.guideline_id in (".implode(",", $sanitized_gids).")
							and g.guideline_id = gg.guideline_id
							and gg.group_id = gs.group_id
							and gs.subgroup_id = gc.subgroup_id
							and gc.check_id = c.check_id
							and c.html_tag = 'all elements'
							and c.open_to_public = 1
						order by c.html_tag";

    return $this->execute($sql);
  }

	/**
	* Return checks NOT for all html elements by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getOpenChecksNotForAllByGuidelineIDs($gids)
	{
		if (!is_array($gids)) return false;
		
		$sanitized_gids = Utility::sanitizeIntArray($gids);
		
		$sql = "select distinct gc.check_id, c.html_tag
							from ". TABLE_PREFIX ."guidelines g, 
									". TABLE_PREFIX ."guideline_groups gg, 
									". TABLE_PREFIX ."guideline_subgroups gs, 
									". TABLE_PREFIX ."subgroup_checks gc,
									". TABLE_PREFIX ."checks c
							where g.guideline_id in (".implode(",", $sanitized_gids).")
								and g.guideline_id = gg.guideline_id
								and gg.group_id = gs.group_id
								and gs.subgroup_id = gc.subgroup_id
								and gc.check_id = c.check_id
								and c.html_tag <> 'all elements'
								and c.open_to_public = 1
							order by c.html_tag";

    return $this->execute($sql);
  }

	/**
	* Return prerequisite checks by given guideline ids
	* @access  public
	* @param   $gids : guideline IDs
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getOpenPreChecksByGuidelineIDs($gids)
	{
		if (!is_array($gids)) return false;
		
		$sanitized_gids = Utility::sanitizeIntArray($gids);
		
		$sql = "select distinct c.check_id, cp.prerequisite_check_id
					from ". TABLE_PREFIX ."guidelines g, 
					     ". TABLE_PREFIX ."guideline_groups gg, 
					     ". TABLE_PREFIX ."guideline_subgroups gs, 
					     ". TABLE_PREFIX ."subgroup_checks gc,
					     ". TABLE_PREFIX ."checks c,
					     ". TABLE_PREFIX ."check_prerequisites cp
					where g.guideline_id in (".implode(",", $sanitized_gids).")
					  and g.guideline_id = gg.guideline_id
					  and gg.group_id = gs.group_id
					  and gs.subgroup_id = gc.subgroup_id
					  and gc.check_id = c.check_id
					  and c.open_to_public = 1
					  and c.check_id = cp.check_id
					order by c.check_id, cp.prerequisite_check_id";

    return $this->execute($sql);
  }

	/**
	* Return checks from the groups which group name is NULL. These groups are created by system
	* @access  public
	* @param   $gid : guideline ID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getGuidelineLevelChecks($gid)
	{
		$sql = "select distinct c.*, gc.subgroup_id
							from ". TABLE_PREFIX ."guideline_groups gg, 
							     ". TABLE_PREFIX ."guideline_subgroups gs, 
							     ". TABLE_PREFIX ."subgroup_checks gc,
							     ". TABLE_PREFIX ."checks c
							where gg.guideline_id = ".intval($gid)."
							  and gg.name is NULL
							  and gg.group_id = gs.group_id
							  and gs.subgroup_id = gc.subgroup_id
							  and gc.check_id = c.check_id
							  and c.open_to_public = 1
							order by c.html_tag";

		return $this->execute($sql);
	}

	/**
	* Return checks from the subgroups which subgroup name is NULL. These subgroups are created by system
	* @access  public
	* @param   $gid : group ID
	* @return  table rows
	* @author  Cindy Qi Li
	*/
	function getGroupLevelChecks($group_id)
	{
		$sql = "select distinct c.*, gc.subgroup_id
							from ". TABLE_PREFIX ."guideline_subgroups gs, 
							     ". TABLE_PREFIX ."subgroup_checks gc,
							     ". TABLE_PREFIX ."checks c
							where gs.group_id = ".intval($group_id)."
							  and gs.name is NULL
							  and gs.subgroup_id = gc.subgroup_id
							  and gc.check_id = c.check_id
							  and c.open_to_public = 1
							order by c.html_tag";

		return $this->execute($sql);
	}

	/**
	* Return array of subgroups info whose name is null, and belong to the given group id
	* @access  public
	* @param   $groupID : group id
	* @return  subgroup id rows : array of subgroup ids, if successful
	*          false : if not successful
	* @author  Cindy Qi Li
	*/
	public function getChecksBySubgroupID($subgroupID)
	{
		$subgroupID = intval($subgroupID);
		
		$sql = "SELECT c.*, ".$subgroupID ." subgroupID
		          FROM ".TABLE_PREFIX."subgroup_checks gs,"
		                .TABLE_PREFIX."checks c
                 WHERE gs.subgroup_id = ".intval($subgroupID)."
                   AND gs.check_id = c.check_id
                   AND c.open_to_public = 1
                 ORDER BY c.html_tag";

		return $this->execute($sql);
	}

	/**
	 * Validate fields preparing for insert and update
	 * @access  private
	 * @param   $html_tag  
	 *          $confidence
	 *          $name
	 *          $err
	 *          $open_to_public
	 * @return  true    if all fields are valid
	 *          false   if any field is not valid
	 * @author  Cindy Qi Li
	 */
	private function isFieldsValid($html_tag, $confidence, $name, $err, $open_to_public)
	{
		global $msg;
		
		$missing_fields = array();

		if ($html_tag == '')
		{
			$missing_fields[] = _AC('html_tag');
		}
		if ($confidence <> KNOWN && $confidence <> LIKELY && $confidence <> POTENTIAL)
		{
			$missing_fields[] = _AC('error_type');
		}
		if ($name == '')
		{
			$missing_fields[] = _AC('name');
		}
		if ($err == '')
		{
			$missing_fields[] = _AC('error');
		}
		if ($open_to_public <> 0 && $open_to_public <> 1)
		{
			$missing_fields[] = _AC('open_to_public');
		}

		if ($missing_fields)
		{
			$missing_fields = implode(', ', $missing_fields);
			$msg->addError(array('EMPTY_FIELDS', $missing_fields));
		}
		
		if (!$msg->containsErrors())
			return true;
		else
			return false;
	}
	
	/**
	 * insert check terms into language_text and update according record in table "checks"
	 * @access  private
	 * @param   $checkID
	 *          $term      : term to create/update into 'language_text' table
	 *          $text      : text to create/update into 'language_text' table
	 *          $fieldName : field name in table 'checks' to update
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	private function updateLang($checkID, $term, $text, $fieldName)
	{
		global $addslashes;
		
		require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
		$langTextDAO = new LanguageTextDAO();
		$langs = $langTextDAO->getByTermAndLang($term, $_SESSION['lang']);

		if (is_array($langs))
		{// term already exists. Only need to update modified text
			if ($langs[0]['text'] <> $addslashes($text)) $langTextDAO->setText($_SESSION['lang'], '_check',$term,$text);
		}
		else
		{
			$langTextDAO->Create($_SESSION['lang'], '_check',$term,$text,'');
			
			$sql = "UPDATE ".TABLE_PREFIX."checks SET ".$fieldName."='".$term."' WHERE check_id=".$checkID;
			$this->execute($sql);
		}
		
		return true;
	}
	
	/**
	 * delete check terms from language_text and update according record in table "checks" to empty
	 * @access  private
	 * @param   $checkID
	 *          $term       : term to delete from 'language_text' table
	 *          $fieldName  : field name in table 'checks' to update
	 * @return  true    if update successfully
	 *          false   if update unsuccessful
	 * @author  Cindy Qi Li
	 */
	private function deleteLang($checkID, $term, $fieldName)
	{
		require_once(AC_INCLUDE_PATH.'classes/DAO/LanguageTextDAO.class.php');
		$langTextDAO = new LanguageTextDAO();

		$langTextDAO->DeleteByVarAndTerm('_check', $term);
			
		$sql = "UPDATE ".TABLE_PREFIX."checks SET ".$fieldName."='' WHERE check_id=".intval($checkID);
		$this->execute($sql);
		
		return true;
	}
}
?>