<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version Information
 *
 * @package   local_rwth_grades_webservices
 * @copyright 2024 Tim Schroeder, RWTH Aachen University <t.schroeder@itc.rwth-aachen.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_rwth_grades_webservices_override_webservice_execution($function, $params) {
    if ($function->name === 'gradereport_user_get_grade_items') {
        $result = call_user_func_array([$function->classname, $function->methodname], $params);
        if (!array_key_exists('usergrades', $result)) {
            return $result;
        }
        foreach ($result['usergrades'] as &$usergrade) {
            if (!array_key_exists('gradeitems', $usergrade)) {
                continue;
            }
            foreach ($usergrade['gradeitems'] as &$gradeitem) {
                // Clean gradeitem name to conform to PARAM_CLEANHTML type.
                $gradeitem['itemname'] = clean_param($gradeitem['itemname'], PARAM_CLEANHTML);
            }
        }
        return $result;
    }
    return false;
}
