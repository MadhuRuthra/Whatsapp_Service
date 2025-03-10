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
// ManageUsersList - start
async function ManageUsersList(req) {
	var logger_all = main.logger_all
    var logger = main.logger
	try {
		  //  Get all the req header data
		const header_token = req.headers['authorization'];
	
		// To initialize a variable with an empty string value
		var get_manage_users = '';
		var whrcondition = ` `;
		var date_filter = '';
		var get_manage_users_1 = '';
		// get all the req data
		var date_filter = req.body.date_filter;
		var status_filter = req.body.status_filter;
		// declare the variables
		var user_id, user_master_id;
		var filter_date_1;
		var filter_date_first;
		var filter_date_second;
// query parameters
		logger_all.info("[ManageUsersList query parameters] : " + JSON.stringify(req.body));
		// To get the User_id
		var get_user = `SELECT * FROM user_management where bearer_token = '${header_token}' AND usr_mgt_status = 'Y' `;
        if(req.body.user_id){
            get_user = get_user + `and user_id = '${req.body.user_id}' `;
        }
        logger_all.info("[select query request] : " +  get_user);
        const get_user_id = await db.query(get_user);
        logger_all.info("[select query response] : " + JSON.stringify(get_user_id));
		if (get_user_id.length == 0) { // If get_user not available send error response to client in ivalid token
			logger_all.info("Invalid Token")
			return { response_code: 0, response_status: 201, response_msg: 'Invalid Token' };
		}
		else {
			// otherwise to get the user details
			user_id = get_user_id[0].user_id;
			user_master_id = get_user_id[0].user_master_id;
		}
	
			if (user_master_id == 1) {  // primary admin is following this to get the parent id
				whrcondition = ` where 1=1 `;
				if (date_filter) { // date filter using for primary_admin

					// var date_filter_1 = date_filter.split("-");
					filter_date_1 = date_filter.split("-");
					filter_date_first = Date.parse(filter_date_1[0]);
					filter_date_second = Date.parse(filter_date_1[1]);
					function dateRange(startDate, endDate, steps = 1) {
						const dateArray = [];
						let currentDate = new Date(startDate);

						while (currentDate <= new Date(endDate)) {
							dateArray.push(new Date(currentDate));

							function convert(dates) {
								var date = new Date(dates),
									mnth = ("0" + (date.getMonth() + 1)).slice(-2),
									day = ("0" + date.getDate()).slice(-2);
								return [date.getFullYear(), mnth, day].join("-");
							}
							slt_date = convert(currentDate);
                            // get_manage_users_1 using 
							get_manage_users_1 += ` SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id  where (date(usr.usr_mgt_entry_date) BETWEEN '${slt_date}' and '${slt_date}') union`;

							currentDate.setUTCDate(currentDate.getUTCDate() + steps);
						}
						return dateArray;
					}
					const dates = dateRange(filter_date_1[0], filter_date_1[1]);
					var lastIndex = get_manage_users_1.lastIndexOf(" ");
					get_manage_users_1 = get_manage_users_1.substring(0, lastIndex);

					get_manage_users = await db.query(get_manage_users_1 + ` order by user_id desc`);
					logger_all.info("select query response] : " + JSON.stringify(get_manage_users))

				}

				else if (status_filter) {  // status filter using for primary_admin
					switch (status_filter != '') {
						case (status_filter.toLowerCase() == 'active'):

							whrcondition = ` where usr.usr_mgt_status = 'Y' `;

							break;

						case (status_filter.toLowerCase() == 'inactive'):
							whrcondition = ` where usr.usr_mgt_status = 'N' `;

							break;
					}
					// get_manage_users using this query response
					logger_all.info("select query request] : " + `SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id ${whrcondition} order by user_id desc`);
					get_manage_users = await db.query(`SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id  ${whrcondition} order by user_id desc`);
					logger_all.info("[select query response] : " + JSON.stringify(get_manage_users))

				} else { // otherwise get_manage_users this query was executed
					logger_all.info("[select query request] : " + `SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id ${whrcondition} order by user_id desc`);
					get_manage_users = await db.query(`SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id  ${whrcondition} order by user_id desc`);
					logger_all.info("[select query response] : " + JSON.stringify(get_manage_users))
				}
			} else if (user_master_id == 2 || user_master_id == 3) { // admin - dept head are following this to get the parent id
				whrcondition = `  1=1 `;

				if (date_filter) { // date filter using
// date function
					filter_date_1 = date_filter.split("-");
					filter_date_first = Date.parse(filter_date_1[0]);
					filter_date_second = Date.parse(filter_date_1[1]);
					function dateRange(startDate, endDate, steps = 1) {
						const dateArray = [];
						let currentDate = new Date(startDate);

						while (currentDate <= new Date(endDate)) {
							dateArray.push(new Date(currentDate));

							function convert(dates) {
								var date = new Date(dates),
									mnth = ("0" + (date.getMonth() + 1)).slice(-2),
									day = ("0" + date.getDate()).slice(-2);
								return [date.getFullYear(), mnth, day].join("-");
							}
							slt_date = convert(currentDate);

							get_manage_users_1 += ` SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id where (date(usr.usr_mgt_entry_date) BETWEEN '${slt_date}' and '${slt_date}') and usr.parent_id = '${user_id}' union`;

							currentDate.setUTCDate(currentDate.getUTCDate() + steps);
						}
						return dateArray;
					}
					const dates = dateRange(filter_date_1[0], filter_date_1[1]);
					var lastIndex = get_manage_users_1.lastIndexOf(" ");
					get_manage_users_1 = get_manage_users_1.substring(0, lastIndex);

					get_manage_users = await db.query(get_manage_users_1 + ` order by user_id desc`);
					logger_all.info("[select query response] : " + JSON.stringify(get_manage_users))

				} else { // otherwise following this get_manage_users query
					logger_all.info("[select query request] : " + `SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id where ${whrcondition} and usr.parent_id = '${user_id}' order by user_id desc`);
					get_manage_users = await db.query(`SELECT usr.user_id, usr.user_name, ums.user_master_id, ums.user_title, prn.user_id parent_id, prn.user_name parent_name, usr.login_id, usr.user_email, usr.user_mobile, usr.usr_mgt_status, usr.usr_mgt_entry_date FROM user_management usr left join user_master ums on usr.user_master_id = ums.user_master_id left join user_management prn on usr.parent_id = prn.user_id where ${whrcondition} and usr.parent_id = '${user_id}' order by user_id desc`);
					logger_all.info("[select query response] : " + JSON.stringify(get_manage_users))
				}
			}
  // if the get_manage_users length is '0' to get the no available data.otherwise it will be return the push the get_manage_users details.
			if (get_manage_users.length == 0) {
				return {
					response_code: 1,
					response_status: 204,
					response_msg: 'No data available'
				};
			} else {
				return {
					response_code: 1,
					response_status: 200,
					num_of_rows: get_manage_users.length,
					response_msg: 'Success',
					report: get_manage_users
				};
			}
		
	} catch (e) {  // any error occurres send error response to client
		logger_all.info("[ManageUsersList failed response] : " + e)
		return {
			response_code: 0,
			response_status: 201,
			response_msg: 'Error occured'
		};
	}
}
// ManageUsersList - end

// using for module exporting
module.exports = {
	ManageUsersList
}