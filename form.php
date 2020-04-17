<?php
require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
require_once('lib.php');

class offjobhours extends moodleform {
	public function definition() {
		global $USER, $DB, $CFG, $OUTPUT;

		$mform = $this->_form;
    $student = get_student($this->_customdata['studentid']);
    $studentdata = get_student_data($student->id);
		$activities = get_current_activities();

		foreach($studentdata as $student=>$value){
			$studentdetails = $value->firstname . ' ' . $value->lastname;
		}
		$mform->addElement('html', '<h3>' . $studentdetails. '</h3><br />');
		foreach($activities as $activity=>$a){
			$mform->addElement('text', 'activity_'.$a->id, $a->activityname);
	    $mform->setType('activity_'.$a->id, PARAM_FLOAT);
			$mform->addRule('activity_'.$a->id, get_string('error'), 'numeric', 'client');
		}
		$mform->addElement('filemanager', 'activity_filemanager', 'Commitment statement', null, array('maxbytes' => 41943040, 'maxfiles' => 1));

    $mform->addElement('hidden', 'studentid', $this->_customdata['studentid']);
    $mform->setType('studentid', PARAM_INT);
    $mform->addElement('hidden', 'courseid', $this->_customdata['courseid']);
    $mform->setType('course', PARAM_INT);
    $this->add_action_buttons();
		$formdata = array();

		foreach($studentdata as $s => $d){
			$formdata['activity_'. $d->activityid] = $d->hours;
			$this->set_data($formdata);
		}
	}
}
