/*
This api has chat API functions which is used to connect the mobile chat.
This page is act as a Backend page which is connect with Node JS API and PHP Frontend.
It will collect the form details and send it to API.
After get the response from API, send it back to Frontend.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const db = require("../../db_connect/connect");
require("dotenv").config();
const main = require('../../logger');
// AvailableCreditsList- start
async function UpdateCreditRaisestatus(req) {
	var logger_all = main.logger_all
    var logger = main.logger
	try {
		
		// get all the req data
		var user_id = req.body.user_id;
var usrsmscrd_id = req.body.usrsmscrd_id;
        var usrsmscrd_status = req.body.usrsmscrd_status;
        var usrsmscrd_status_comments = req.body.usrsmscrd_status_comments;
// query parameters
		logger_all.info("[Rppayment_usrsmscrd_id query parameters] : " + JSON.stringify(req.body));
// get_available_message to execute this query
			logger_all.info("[select query request] : " + `UPDATE user_sms_credit_raise SET usrsmscrd_status = '${usrsmscrd_status}', usrsmscrd_status_cmnts = '${usrsmscrd_status_comments}' WHERE usrsmscrd_id = ' ${usrsmscrd_id}' `);
			const Rppayment_usrsmscrd_id = await db.query(`UPDATE user_sms_credit_raise SET usrsmscrd_status = '${usrsmscrd_status}', usrsmscrd_status_cmnts = '${usrsmscrd_status_comments}' WHERE usrsmscrd_id = '${usrsmscrd_id}' `);
			logger_all.info("[select query response] : " + JSON.stringify(Rppayment_usrsmscrd_id));

				
// if the get_available_message length is not available to send the no available data.otherwise it will be return the get_available_message details.
			if (Rppayment_usrsmscrd_id.length == 0) {
				return { response_code: 1, response_status: 204, response_msg: 'No data available' };
			}
			else {
				return { response_code: 1, response_status: 200, num_of_rows: Rppayment_usrsmscrd_id.length, response_msg: 'Success', report: Rppayment_usrsmscrd_id };
			}
	
}
	catch (e) {// any error occurres send error response to client
		logger_all.info("[Rppayment_usrsmscrd_id failed response] : " + e)
		return { response_code: 0, response_status: 201, response_msg: 'Error occured' };
	}
}
// AvailableCreditsList - end

// using for module exporting
module.exports = {
	UpdateCreditRaisestatus,
}


