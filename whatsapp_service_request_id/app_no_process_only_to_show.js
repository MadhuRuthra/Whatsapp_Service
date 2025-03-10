/*
API that allows your frontend to communicate with your backend server (Node.js) for processing and retrieving data.
To access a MySQL database with Node.js and can be use it.
This is a main page for starting API the process.This page to routing the subpages page and then process are executed.

Version : 1.0
Author : Madhubala (YJ0009)
Date : 05-Jul-2023
*/
// Import the required packages and libraries
const https = require("https");
const express = require("express");
const dotenv = require('dotenv');
dotenv.config();
// const router = express.Router();
var cors = require("cors");
var axios = require('axios');
const csv = require("csv-stringify");
const fetch = require('node-fetch');

// Database Connections
const app = express();
const port = 10020;
const db = require("./db_connect/connect");
const dynamic_db = require("./db_connect/dynamic_connect");

const validator = require('./validation/middleware')

const bodyParser = require('body-parser');
const fs = require('fs');
const log_file = require('./logger')
const logger = log_file.logger;
const logger_all = log_file.logger_all;

const options = {
    key: fs.readFileSync("/etc/letsencrypt/live/yourpostman.in/privkey.pem"),
    cert: fs.readFileSync("/etc/letsencrypt/live/yourpostman.in/cert.pem")
};

const httpServer = https.createServer(options, app);
const io = require('socket.io')(httpServer, {
    cors: {
        origin: "*",
    },
});

// Process Validations
const bigqueryValidation = require("./validation/big_query_validation");
const composeMsgValidation = require("./validation/send_message_validation");
const createTemplateValidation = require("./validation/template_approval_validation");
const UserApprovalValidation = require("./validation/user_approval_validation");
const CreateCsvValidation = require("./validation/create_csv_validation");

const Logout = require("./logout/route");
const Login = require("./login/route");
const Template = require("./api_request/template/template_route");
const Message = require("./api_request/send_messages/send_message_route");
const Report = require("./api_request/report/report_route");
const List = require("./api_request/list/list_route");
const SenderId = require("./api_request/sender_id/sender_id_route");
const Chat = require("./api_request/chat/chat_route");
const DeviceId = require("./api_request/update_device_token/route_devicetoken");
const valid_user = require("./validation/valid_user_middleware");
const Upload = require("./api_request/upload/upload_route");
const dashboard = require("./api_request/dashboard/dashboard_route");
const getHeaderFile = require('./api_request/template/getHeader');

const testing = require('./api_request/testing/testing_route');
const env = process.env

const api_url = env.API_URL;
const media_bearer = env.MEDIA_BEARER;
const media_storage = env.MEDIA_STORAGE;

// Current Date and Time
// var today = new Date().toLocaleString("en-IN", {timeZone: "Asia/Kolkata"});
var day = new Date();

// Log file Generation based on the current date
var util = require('util');
var exec = require('child_process').exec;

app.use(cors());
app.use(express.json({ limit: '50mb' }));
app.use(
    express.urlencoded({
        extended: true,
        limit: '50mb'
    })
);

// Allows you to send emits from express
app.use(function (request, response, next) {
    request.io = io;
    next();
});

app.get("/", async (req, res) => {
    console.log(day)
    res.json({ message: "okkkk" });
});
function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

// parse application/x-www-form-urlencoded
app.use(bodyParser.urlencoded({ extended: false }));

// parse application/json
app.use(bodyParser.json());

// API initialzation
app.use("/login", Login);
app.use("/template", Template);
app.use("/message", Message);
app.use("/report", Report);
app.use("/list", List);
app.use("/sender_id", SenderId);
app.use("/chat", Chat);
app.use("/devicetoken", DeviceId);
app.use("/logout", Logout);
app.use("/upload_media", Upload);
app.use("/dashboard", dashboard);

app.use("/test", testing);

// api for approve user
app.post("/approve_user", validator.body(UserApprovalValidation),
    valid_user, async (req, res) => {

        try {

            var header_json = req.headers;
            let ip_address = header_json['x-forwarded-for'];
            // get the data from req.body to update in DB
            let mobile_number = req.body.mobile_number;
            let phone_number_id = req.body.phone_number_id;
            let whatsapp_business_acc_id = req.body.whatsapp_business_acc_id;
            let bearer_token = req.body.bearer_token;

            // succ_template for store all the success template in automatic template creation and failed_template for store all the failed template in automatic template creation
            var succ_template = [];
            var failed_template = [];

            logger.info("[user approve query parameters] : " + JSON.stringify(req.body));

            const insert_api_log = `INSERT INTO api_log VALUES(NULL,'${req.originalUrl}','${ip_address}','${req.body.request_id}','N','-','0000-00-00 00:00:00','Y',CURRENT_TIMESTAMP)`
            logger_all.info("[insert query request] : " + insert_api_log);
            const insert_api_log_result = await db.query(insert_api_log);
            logger_all.info("[insert query response] : " + JSON.stringify(insert_api_log_result))

            const check_req_id = `SELECT * FROM api_log WHERE request_id = '${req.body.request_id}' AND response_status != 'N' AND log_status='Y'`
            logger_all.info("[select query request] : " + check_req_id);
            const check_req_id_result = await db.query(check_req_id);
            logger_all.info("[select query response] : " + JSON.stringify(check_req_id_result));

            if (check_req_id_result.length != 0) {

                logger_all.info("[failed response] : Request already processed");
                logger.info("[API RESPONSE] " + JSON.stringify({ request_id: req.body.request_id, response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Request already processed' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id });

            }

            // To check the given mobile number is available or not
            logger_all.info("[select query request] : " + `SELECT * from whatsapp_config WHERE concat(country_code, mobile_no) = '${mobile_number}' AND is_qr_code='N'`)
            const check_user = await db.query(`SELECT * from whatsapp_config WHERE concat(country_code, mobile_no) = '${mobile_number}' AND is_qr_code='N'`);
            logger_all.info("[select query response] : " + JSON.stringify(check_user))

            // if user not available send error response to client
            if (check_user.length == 0) {
                logger_all.info("[user not available] : " + mobile_number)
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Invalid user.', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Invalid user' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                res.json({ response_code: 0, response_status: 201, response_msg: 'Invalid user.', request_id: req.body.request_id });
            }
            else {

                // if user is available then process the will be continued
                // check the whatsapp_config_status is Y. If Yes send error response to the client. If not process will be continued.	

                if (check_user[0].whatspp_config_status == 'Y') {

                    logger_all.info("[user already available] : " + mobile_number)
                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'User already exists.', request_id: req.body.request_id }))

                    var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'User already exists' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    logger.silly("[update query request] : " + log_update);
                    const log_update_result = await db.query(log_update);
                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    res.json({ response_code: 0, response_status: 201, response_msg: 'User already exists.', request_id: req.body.request_id });
                }

                else {
                    // update the phone number id, whatsapp_business_acc_id, bearer token for the given mobile number
                    logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET phone_number_id = '${phone_number_id}',whatsapp_business_acc_id = '${whatsapp_business_acc_id}',bearer_token = '${bearer_token}',whatspp_config_status = 'Y',whatspp_config_apprdate = CURRENT_TIMESTAMP,available_credit=200 WHERE concat(country_code, mobile_no) = '${mobile_number}' AND whatspp_config_status = 'N'`)
                    const update_user = await db.query(`UPDATE whatsapp_config SET phone_number_id = '${phone_number_id}',whatsapp_business_acc_id = '${whatsapp_business_acc_id}',bearer_token = '${bearer_token}',whatspp_config_status = 'Y',whatspp_config_apprdate = CURRENT_TIMESTAMP,available_credit=200 WHERE concat(country_code, mobile_no) = '${mobile_number}' AND whatspp_config_status = 'N'`);
                    logger_all.info("[update query response] : " + JSON.stringify(update_user))

                    // logger_all.info("[select query request] : " + `SELECT * from whatsapp_config WHERE concat(country_code, mobile_no) = '${mobile_number}' AND whatspp_config_status = 'Y'`)
                    // const select_user = await db.query(`SELECT * from whatsapp_config WHERE concat(country_code, mobile_no) = '${mobile_number}' AND whatspp_config_status = 'Y'`);
                    // logger_all.info("[select query response] : " + JSON.stringify(select_user))

                    // if (select_user.length != 0) {

                    // check if the user have a any user. Based on the user we will get all approved templates
                    logger_all.info("[select query request] : " + `SELECT * from whatsapp_config WHERE user_id = '${check_user[0].user_id}' AND whatspp_config_status = 'Y' ORDER BY whatspp_config_id ASC`)
                    const select_id = await db.query(`SELECT * from whatsapp_config WHERE user_id = '${check_user[0].user_id}' AND whatspp_config_status = 'Y' ORDER BY whatspp_config_id ASC`);
                    logger_all.info("[select query response] : " + JSON.stringify(select_id))

                    // if user doesn't have any user, means this is the first user then no need to request templates.
                    if (select_id.length > 1) {

                        // api url to get the all template for the existing user
                        api_url_updated = `${api_url}${select_id[0].whatsapp_business_acc_id}/message_templates`

                        var get_temp = {
                            method: 'get',
                            url: api_url_updated,
                            headers: {
                                'Authorization': 'Bearer ' + select_id[0].bearer_token,
                            }
                        };

                        logger_all.info("[template get request] : " + JSON.stringify(get_temp))
                        // process api request
                        await axios(get_temp)
                            .then(async function (response) {

                                logger_all.info("[template response] : " + JSON.stringify(response.data))

                                // api url to the given user to request template
                                var api_url_user = `${api_url}${whatsapp_business_acc_id}/message_templates`

                                // if no template available then send success repsonse to client.
                                if (response.data.data.length == 0) {
                                    logger_all.info("[ No template available] : " + mobile_number)
                                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'No templates available.', request_id: req.body.request_id }))

                                    var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'No templates available' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                    logger.silly("[update query request] : " + log_update);
                                    const log_update_result = await db.query(log_update);
                                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                    res.json({ response_code: 1, response_status: 200, response_msg: 'No templates available.', request_id: req.body.request_id });
                                }

                                // loop all the approved template to send same template request for the given sender id
                                for (var i = 0; i < response.data.data.length; i++) {

                                    // check if the template approved otherwise we are not going to send request for this template
                                    if (response.data.data[i].status = 'APPROVED') {
                                        // flag to check if the template has media
                                        var isMedia = false;
                                        var mediaPlace;
                                        var variable_status = 0;

                                        // check if the template is in our db
                                        logger_all.info("[select query request] : " + `SELECT * FROM message_template tmp
											LEFT JOIN master_language lan on lan.language_id = tmp.language_id
											WHERE tmp.template_name = '${response.data.data[i].name}' AND lan.language_code = '${response.data.data[i].language}' AND tmp.template_status = 'Y'`)
                                        const select_lang = await db.query(`SELECT * FROM message_template tmp
											LEFT JOIN master_language lan on lan.language_id = tmp.language_id
											WHERE tmp.template_name = '${response.data.data[i].name}' AND lan.language_code = '${response.data.data[i].language}' AND tmp.template_status = 'Y'`);
                                        logger_all.info("[select query response] : " + JSON.stringify(select_lang))

                                        if (select_lang.length != 0) {

                                            // loop all the components to check if the template have variables and media. 
                                            for (var p = 0; p < response.data.data[i].components.length; p++) {

                                                // if template has variables set variable_status as how many variables the template has.
                                                if (response.data.data[i].components[p]['type'] == 'body' || response.data.data[i].components[p]['type'] == 'BODY') {
                                                    if (response.data.data[i].components[p]['example']) {
                                                        variable_status = response.data.data[i].components[p]['example']['body_text'][0].length
                                                    }
                                                }

                                                // if template has media set ismedia flag as true and set the position of the media json in mediaplace
                                                if (response.data.data[i].components[p]['type'] == 'HEADER' && response.data.data[i].components[p]['format'] != 'TEXT') {
                                                    logger_all.info("Media found")
                                                    isMedia = true;
                                                    mediaPlace = p;
                                                }
                                            }

                                            // insert template in db
                                            logger_all.info("[insert query request] : " + `INSERT INTO message_template VALUES(NULL,${check_user[0].whatspp_config_id},'${select_lang[0].unique_template_id}','${response.data.data[i].name},${select_lang[0].language_id},'${response.data.data[i].category}','${JSON.stringify(response.data.data[i].components)}','-',1,'N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00',${variable_status})`)
                                            const insert_new_usr = await db.query(`INSERT INTO message_template VALUES(NULL,${check_user[0].whatspp_config_id},'${select_lang[0].unique_template_id}','${response.data.data[i].name}',${select_lang[0].language_id},'${response.data.data[i].category}','${JSON.stringify(response.data.data[i].components)}','-',1,'N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00',${variable_status})`);
                                            logger_all.info("[insert query response] : " + JSON.stringify(insert_new_usr))

                                            // ismedia found in this template. we have to get the media and get the header_handle value of the media by using the below facebbok api
                                            if (isMedia) {
                                                // url of the media
                                                var h_file = await getHeaderFile(response.data.data[i].components[mediaPlace]['example']['header_handle'][0]);

                                                if (h_file) {

                                                    // send request to the api to get the header_handle
                                                    var command = `curl -X POST \
                            "${api_url}${h_file[0]}" \
                            --header "Authorization: OAuth ${media_bearer}" \
                            --header "file_offset: 0" \
                            --data-binary @${h_file[1]}`

                                                    child = exec(command, async function (error, stdout, stderr) {

                                                        logger_all.info(' stdout: ' + stdout);
                                                        logger_all.info(' stderr: ' + stderr);

                                                        var curl_output = JSON.parse(stdout);
                                                        fs.unlinkSync(h_file[1]);
                                                        header_value = curl_output.h;
                                                        // return curl_output.h
                                                        // })

                                                        // after got the header_handle value replace the old header_handle with new one.
                                                        response.data.data[i].components[mediaPlace]['example']['header_handle'][0] = header_value;

                                                        // json for request a template
                                                        var tmpl_data = {
                                                            name: response.data.data[i].name,
                                                            language: response.data.data[i].language,
                                                            category: response.data.data[i].category,
                                                            components: response.data.data[i].components
                                                        }

                                                        var post_temp = {
                                                            method: 'post',
                                                            url: api_url_user,
                                                            headers: {
                                                                'Authorization': 'Bearer ' + select_id[0].bearer_token,
                                                            },
                                                            params: tmpl_data
                                                        };

                                                        logger_all.info("[template post request] : " + JSON.stringify(post_temp))

                                                        // request to the api to create template in facebook
                                                        await axios(post_temp)
                                                            .then(async function (response) {

                                                                logger_all.info("[template response] : " + JSON.stringify(response.data))

                                                                // if successfully requested, then update the template status and template id
                                                                logger_all.info("[update query request] : " + `UPDATE message_template SET template_response_id = '${response.data.id}',template_status = 'S' WHERE template_id = ${insert_new_usr.insertId}`)
                                                                const update_succ = await db.query(`UPDATE message_template SET template_response_id = '${response.data.id}',template_status = 'S' WHERE template_id = ${insert_new_usr.insertId}`);
                                                                logger_all.info("[update query response] : " + JSON.stringify(update_succ))

                                                                // push the success template in succ_template
                                                                succ_template.push({ template_name: response.data.data[i].name })

                                                                // check if this is the last template, so we can send response to client.
                                                                if (i == response.data.data.length - 1) {
                                                                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                                                    var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                                                    logger.silly("[update query request] : " + log_update);
                                                                    const log_update_result = await db.query(log_update);
                                                                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                                                    res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                                                }

                                                            })
                                                            .catch(async function (error) {
                                                                logger_all.info("[template approval failed response] : " + error)

                                                                // if any error or failure, update the template status as F
                                                                logger_all.info("[update query request] : " + `UPDATE message_template SET template_status = 'F' WHERE template_id = ${insert_new_usr.insertId}`)
                                                                const update_failure_temp = await db.query(`UPDATE message_template SET template_status = 'F' WHERE template_id = ${insert_new_usr.insertId}`);
                                                                logger_all.info("[update query response] : " + JSON.stringify(update_failure_temp))

                                                                // push the failed template in failed_template array
                                                                failed_template.push({ template_name: response.data.data[i].name, reason: error.message })

                                                                // check if this is the last template, so we can send response to client.
                                                                if (i == response.data.data.length - 1) {
                                                                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                                                    var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                                                    logger.silly("[update query request] : " + log_update);
                                                                    const log_update_result = await db.query(log_update);
                                                                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                                                    res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                                                }
                                                            })
                                                    })
                                                }
                                            }
                                            else {
                                                // if template doesn't have media directly we can request template
                                                var tmpl_data = {
                                                    name: response.data.data[i].name,
                                                    language: response.data.data[i].language,
                                                    category: response.data.data[i].category,
                                                    components: response.data.data[i].components
                                                }

                                                var post_temp = {
                                                    method: 'post',
                                                    url: api_url_user,
                                                    headers: {
                                                        'Authorization': 'Bearer ' + select_id[0].bearer_token,
                                                    },
                                                    params: tmpl_data
                                                };

                                                logger_all.info("[template post request] : " + JSON.stringify(post_temp))

                                                await axios(post_temp)
                                                    .then(async function (response) {

                                                        logger_all.info("[template response] : " + JSON.stringify(response.data))

                                                        // if successfully requested, then update the template status and template id
                                                        logger_all.info("[update query request] : " + `UPDATE message_template SET template_response_id = '${response.data.id}',template_status = 'S' WHERE template_id = ${insert_new_usr.insertId}`)
                                                        const update_succ = await db.query(`UPDATE message_template SET template_response_id = '${response.data.id}',template_status = 'S' WHERE template_id = ${insert_new_usr.insertId}`);
                                                        logger_all.info("[update query response] : " + JSON.stringify(update_succ))

                                                        // push the success template in succ_template
                                                        succ_template.push({ template_name: response.data.data[i].name })

                                                        // check if this is the last template, so we can send response to client.
                                                        if (i == response.data.data.length - 1) {
                                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                                            var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                                            logger.silly("[update query request] : " + log_update);
                                                            const log_update_result = await db.query(log_update);
                                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                                            res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                                        }

                                                    })
                                                    .catch(async function (error) {
                                                        logger_all.info("[template approval failed response] : " + error)

                                                        // if any error or failure, update the template status as F
                                                        logger_all.info("[update query request] : " + `UPDATE message_template SET template_status = 'F' WHERE template_id = ${insert_new_usr.insertId}`)
                                                        const update_failure_temp = await db.query(`UPDATE message_template SET template_status = 'F' WHERE template_id = ${insert_new_usr.insertId}`);
                                                        logger_all.info("[update query response] : " + JSON.stringify(update_failure_temp))

                                                        // push the failed template in failed_template array
                                                        failed_template.push({ template_name: response.data.data[i].name, reason: error.message })

                                                        // check if this is the last template, so we can send response to client.
                                                        if (i == response.data.data.length - 1) {
                                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                                            var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                                            logger.silly("[update query request] : " + log_update);
                                                            const log_update_result = await db.query(log_update);
                                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                                            res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                                        }
                                                    })
                                            }
                                        }
                                        else {
                                            // if the template not available in db then we are not going to request for that template
                                            logger_all.info("[template not available] : " + response.data.data[i].name + " - " + response.data.data[i].language)
                                            // push the failed template in failed_template array
                                            failed_template.push({ template_name: response.data.data[i].name, reason: 'Template not available' })

                                            // check if this is the last template, so we can send response to client.
                                            if (i == response.data.data.length - 1) {
                                                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                                var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                                logger.silly("[update query request] : " + log_update);
                                                const log_update_result = await db.query(log_update);
                                                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                                res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                            }

                                        }
                                    }
                                    else {
                                        // template not approved by facebook then we are not going to request for that template
                                        logger_all.info("[template not approved] : " + response.data.data[i].name + " - " + response.data.data[i].language)
                                        // push the failed template in failed_template array
                                        failed_template.push({ template_name: response.data.data[i].name, reason: 'Template not approved' })

                                        // check if this is the last template, so we can send response to client
                                        if (i == response.data.data.length - 1) {
                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id }))

                                            var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                            logger.silly("[update query request] : " + log_update);
                                            const log_update_result = await db.query(log_update);
                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                            res.json({ response_code: 1, response_status: 200, response_msg: 'Success', succ_template: succ_template, failed_template: failed_template, request_id: req.body.request_id });
                                        }
                                    }
                                }

                            })
                            .catch(async function (error) {
                                // any error occurres send error response to client
                                logger_all.info("[user approval failed response] : " + error)
                                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Error occurred while getting templates', request_id: req.body.request_id }))

                                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Error occurred while getting templates' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                logger.silly("[update query request] : " + log_update);
                                const log_update_result = await db.query(log_update);
                                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                res.json({ response_code: 0, response_status: 201, response_msg: 'Error occurred while getting templates', request_id: req.body.request_id });

                            })
                    }
                    else {
                        // if this is the new user we don't have to request for any template.
                        logger_all.info("[New user - No template available] : " + mobile_number)
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'New user', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'New user' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        res.json({ response_code: 1, response_status: 200, response_msg: 'Success', request_id: req.body.request_id });

                    }

                }
            }
        }

        catch (e) {
            // any error occurres send error response to client
            logger_all.info("[user approval failed response] : " + e)
            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Error occurred', request_id: req.body.request_id }))

            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Error occurred' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
            logger.silly("[update query request] : " + log_update);
            const log_update_result = await db.query(log_update);
            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

            res.json({ response_code: 0, response_status: 201, response_msg: 'Error occurred', request_id: req.body.request_id });

        }
    })

// api for create a template
app.post("/create_template", validator.body(createTemplateValidation),
    valid_user, async (req, res) => {

        try {
            const logger_all = log_file.logger_all;

            var header_json = req.headers;
            let ip_address = header_json['x-forwarded-for'];

            // get current_year to generate a template name
            var current_year = day.getFullYear().toString();

            // get today's julian date to generate template name
            Date.prototype.julianDate = function () {
                var j = parseInt((this.getTime() - new Date('Dec 30,' + (this.getFullYear() - 1) + ' 23:00:00').getTime()) / 86400000).toString(),
                    i = 3 - j.length;
                while (i-- > 0) j = 0 + j;
                return j
            };

            // get all the data from the api body and headers
            let api_bearer = req.headers.authorization;
            let language = req.body.language;
            let temp_category = req.body.category;
            let temp_components = req.body.components;
            let temp_details = req.body.code;
            let media_url = req.body.media_url

            //  initialize required variable and arrays.
            var succ_array = [];
            var error_array = [];
            var count = 0;
            let temp_insert_ids = [];
            let sender_number = [];
            let sender_number_business_id = [];
            let sender_number_bearer_token = [];
            var user_id;
            var variable_count = 0;
            var user_short_name;
            var full_short_name;
            var user_master;
            var unique_id;
            var h_file;
            var media_type;

            const insert_api_log = `INSERT INTO api_log VALUES(NULL,'${req.originalUrl}','${ip_address}','${req.body.request_id}','N','-','0000-00-00 00:00:00','Y',CURRENT_TIMESTAMP)`
            logger_all.info("[insert query request] : " + insert_api_log);
            const insert_api_log_result = await db.query(insert_api_log);
            logger_all.info("[insert query response] : " + JSON.stringify(insert_api_log_result))

            const check_req_id = `SELECT * FROM api_log WHERE request_id = '${req.body.request_id}' AND response_status != 'N' AND log_status='Y'`
            logger_all.info("[select query request] : " + check_req_id);
            const check_req_id_result = await db.query(check_req_id);
            logger_all.info("[select query response] : " + JSON.stringify(check_req_id_result));

            if (check_req_id_result.length != 0) {

                logger_all.info("[failed response] : Request already processed");
                logger.info("[API RESPONSE] " + JSON.stringify({ request_id: req.body.request_id, response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Request already processed' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id });

            }

            // if req.body contains user_id we are checking both user_id and bearer token are valid and store some information like short_name for generate a template name
            if (req.body.user_id) {
                user_id = req.body.user_id;
                logger_all.info("[select query request] : " + `SELECT * FROM user_management WHERE bearer_token = '${api_bearer}' AND user_id = '${user_id}'`)
                const get_user_id = await db.query(`SELECT * FROM user_management WHERE bearer_token = '${api_bearer}' AND user_id = '${user_id}'`);
                logger_all.info("[select query response] : " + JSON.stringify(get_user_id))
                user_short_name = get_user_id[0].user_short_name;
                user_master = get_user_id[0].parent_id;

            }
            else {
                // if user_id not received in req.body we will get if using bearer token.
                logger_all.info("[select query request] : " + `SELECT * FROM user_management WHERE bearer_token = '${api_bearer}' AND usr_mgt_status = 'Y'`)
                const get_user_id = await db.query(`SELECT * FROM user_management WHERE bearer_token = '${api_bearer}' AND usr_mgt_status = 'Y'`);
                logger_all.info("[select query response] : " + JSON.stringify(get_user_id))

                user_id = get_user_id[0].user_id;
                user_short_name = get_user_id[0].user_short_name;
                user_master = get_user_id[0].parent_id;
            }

            // get the given user's master short name 
            logger_all.info("[select query request] : " + `SELECT usr1.user_short_name FROM user_management usr
			LEFT JOIN user_management usr1 on usr.parent_id = usr1.user_id
			WHERE usr.user_short_name = '${user_short_name}'`)
            const get_user_short_name = await db.query(`SELECT usr1.user_short_name FROM user_management usr
			LEFT JOIN user_management usr1 on usr.parent_id = usr1.user_id
			WHERE usr.user_short_name = '${user_short_name}'`);
            logger_all.info("[select query response] : " + JSON.stringify(get_user_short_name))

            // if nothing returns set given user's short_name as full_short_name
            if (get_user_short_name.length == 0) {
                full_short_name = user_short_name;
            }
            else {
                // if the given user is primary admin then no master shouldn't be there. so set given user's short_name as full_short_name
                if (user_master == 1 || user_master == '1') {
                    full_short_name = user_short_name;
                }
                // concat the given user's master short_name in given user's short_name
                else {
                    full_short_name = `${get_user_short_name[0].user_short_name}_${user_short_name}`;
                }
            }

            // get the unique_serial_number to generate unique template name
            logger_all.info("[select query request] : " + `SELECT unique_template_id FROM message_template ORDER BY template_id DESC limit 1`)
            const get_unique_id = await db.query(`SELECT unique_template_id FROM message_template ORDER BY template_id DESC limit 1`);
            logger_all.info("[select query response] : " + JSON.stringify(get_unique_id))

            // if nothing returns this is going to be a first template so make it as 001
            if (get_unique_id.length == 0) {
                unique_id = '001'
            }
            else {
                // get the serial_number of the latest template
                var serial_id = get_unique_id[0].unique_template_id.substr(get_unique_id[0].unique_template_id.length - 3)
                var temp_id = parseInt(serial_id) + 1;

                // add 0 as per our need
                if (temp_id.toString().length == 1) {
                    unique_id = '00' + temp_id;
                }
                if (temp_id.toString().length == 2) {
                    unique_id = '0' + temp_id;
                }
                if (temp_id.toString().length == 3) {
                    unique_id = temp_id;
                }
            }

            var tmp_details;
            var tmp_details_test;

            // if receive media_url get the media type of the media
            if (media_url) {
                h_file = await getHeaderFile.getHeaderFile(media_url);
            }

            // check the template code is received to make the pld code work
            if (!temp_details) {

                // initialize the code 
                tmp_details = '000000000';

                // function to set the character ina string at a specific position
                function setCharAt(index, chr) {
                    if (index > tmp_details.length - 1) return tmp_details;
                    tmp_details = tmp_details.substring(0, index) + chr + tmp_details.substring(index + 1);
                    return tmp_details.substring(0, index) + chr + tmp_details.substring(index + 1);
                }

                // check the template have english text or other language, media or not, buttons - to validate the template have all of the components as mentioned in the template_code.
                // if it is not same, then something is missing we send a error response to client
                for (var p = 0; p < temp_components.length; p++) {

                    // check the body have variables and the template language is english or not
                    if (temp_components[p]['type'] == 'body' || temp_components[p]['type'] == 'BODY') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        if (temp_components[p]['example']) {
                            variable_count = temp_components[p]['example']['body_text'][0].length
                        }
                        if (language == 'en_US' || language == 'en_GB') {
                            setCharAt(0, "t");
                        }
                        else {
                            setCharAt(0, "l");
                        }
                    }

                    // check the header has text
                    if (temp_components[p]['type'] == 'HEADER') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        setCharAt(1, "h");
                    }

                    // check the template has footer
                    if (temp_components[p]['type'] == 'FOOTER') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        setCharAt(8, "f");
                    }

                    // check the template has button, and which type of buttons they have.
                    if (temp_components[p]['type'] == 'BUTTONS') {
                        for (var b = 0; b < temp_components[p]['buttons'].length; b++) {
                            if (temp_components[p]['buttons'][b]['type'] == 'URL') {
                                setCharAt(6, "u");
                            }
                            if (temp_components[p]['buttons'][b]['type'] == 'QUICK_REPLY') {
                                setCharAt(7, "r");
                            }

                            if (temp_components[p]['buttons'][b]['type'] == 'PHONE_NUMBER') {
                                setCharAt(5, "c");
                            }

                        }

                    }

                    // check the template has which type of media
                    if (media_url) {
                        //h_file = await getHeaderFile.getHeaderFile(media_url);
                        if (h_file[2] == 'IMAGE') {
                            setCharAt(2, "i");
                            media_type = 'IMAGE'
                        }

                        else if (h_file[2] == 'VIDEO') {
                            setCharAt(3, "v");
                            media_type = 'VIDEO'
                        }

                        else if (h_file[2] == 'DOCUMENT') {
                            setCharAt(4, "d");
                            media_type = 'DOCUMENT'
                        }

                    }
                }
            }
            // this block doing the same work as the previos block
            else {

                tmp_details_test = '000000000';
                function setCharAtTest(index, chr) {
                    if (index > tmp_details_test.length - 1) return tmp_details_test;
                    tmp_details_test = tmp_details_test.substring(0, index) + chr + tmp_details_test.substring(index + 1);
                    return tmp_details_test.substring(0, index) + chr + tmp_details_test.substring(index + 1);
                }

                if (temp_details[2].toString() == 'i') {
                    if (temp_details[3].toString() != '0' || temp_details[4].toString() != '0') {
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id });
                    }
                    media_type = 'IMAGE'
                    setCharAtTest(2, "i");
                }

                else if (temp_details[3].toString() == 'v') {
                    if (temp_details[2].toString() != '0' || temp_details[4].toString() != '0') {
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id });
                    }
                    media_type = 'VIDEO'
                    setCharAtTest(3, "v");
                }

                else if (temp_details[4].toString() == 'd') {
                    if (temp_details[3].toString() != '0' || temp_details[2].toString() != '0') {
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code.', request_id: req.body.request_id });
                    }
                    media_type = 'DOCUMENT'
                    setCharAtTest(4, "d");
                }

                for (var p = 0; p < temp_components.length; p++) {
                    if (temp_components[p]['type'] == 'body' || temp_components[p]['type'] == 'BODY') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        if (temp_components[p]['example']) {
                            variable_count = temp_components[p]['example']['body_text'][0].length
                        }
                        if (language == 'en_US' || language == 'en_GB') {
                            setCharAtTest(0, "t");
                        }
                        else {
                            setCharAtTest(0, "l");
                        }

                    }

                    if (temp_components[p]['type'] == 'HEADER') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        setCharAtTest(1, "h");
                    }

                    if (temp_components[p]['type'] == 'FOOTER') {
                        temp_components[p]['text'] = temp_components[p]['text'].replace(/&amp;/g, "&")
                        setCharAtTest(8, "f");
                    }
                    if (temp_components[p]['type'] == 'BUTTONS') {
                        for (var b = 0; b < temp_components[p]['buttons'].length; b++) {
                            if (temp_components[p]['buttons'][b]['type'] == 'URL') {
                                setCharAtTest(6, "u");
                            }
                            if (temp_components[p]['buttons'][b]['type'] == 'QUICK_REPLY') {
                                setCharAtTest(7, "r");
                            }

                            if (temp_components[p]['buttons'][b]['type'] == 'PHONE_NUMBER') {
                                setCharAtTest(5, "c");
                            }

                        }

                    }
                }

                // if media found in the component
                if (media_type) {
                    // if media type found but media url not in request media is required. so we send error response to the client
                    if (!media_url) {
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id });
                    }
                    // if media_url is in the request
                    else {
                        // check the type of media. if we receive .mp4 file and media_type image, it is not going to work. Checked here and send error response to the client
                        if (media_type == 'IMAGE' || media_type == 'VIDEO') {
                            if (media_type != h_file[2]) {
                                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch media type.', request_id: req.body.request_id }))

                                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch media type' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                logger.silly("[update query request] : " + log_update);
                                const log_update_result = await db.query(log_update);
                                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch media type.', request_id: req.body.request_id });
                            }
                        }
                    }
                }

                else {
                    // if media_type not found but we recieve media_url we send error response to the client
                    if (media_url) {
                        logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id }))

                        var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                        logger.silly("[update query request] : " + log_update);
                        const log_update_result = await db.query(log_update);
                        logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                        return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id });
                    }
                }

                // if both our template code and request template code are not same, send error response to the client
                if (tmp_details_test != temp_details) {
                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id }))

                    var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Mismatch code' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    logger.silly("[update query request] : " + log_update);
                    const log_update_result = await db.query(log_update);
                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    return res.json({ response_code: 0, response_status: 201, response_msg: 'Mismatch code', request_id: req.body.request_id });
                }

                // if everything fine assign the recieved template code in one variable
                tmp_details = temp_details;
            }
            logger_all.info("*****************");
            // generate unique template name 
            let temp_name = `te_${full_short_name}_${tmp_details}_${current_year.substring(2)}${day.getMonth() + 1}${day.getDate()}_${unique_id}`;
            let unique_template_id = `tmplt_${full_short_name}_${new Date().julianDate()}_${unique_id}`;

            // get the all sender number which are mapped to the user
            logger_all.info("[select query request] : " + `SELECT whatspp_config_id, user_id, concat(country_code,mobile_no) mobile_no, bearer_token,phone_number_id,whatsapp_business_acc_id FROM whatsapp_config where (user_id = '${user_id}' or user_id in ('${user_id}')) and whatspp_config_status = 'Y' AND is_qr_code = 'N'`)
            const mobile_number = await db.query(`SELECT whatspp_config_id, user_id, concat(country_code,mobile_no) mobile_no, bearer_token,phone_number_id,whatsapp_business_acc_id FROM whatsapp_config where (user_id = '${user_id}' or user_id in ('${user_id}')) and whatspp_config_status = 'Y' AND is_qr_code = 'N'`);
            logger_all.info("[select query response] : " + JSON.stringify(mobile_number))

            // if the user has nothing send error response to the client  
            if (mobile_number.length == 0) {
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'No number available', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'No number available' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                res.json({ response_code: 0, response_status: 201, response_msg: 'No number available', request_id: req.body.request_id });
            }
            else {

                // loop fo all the sender_number the user have
                for (var i = 0; i < mobile_number.length; i++) {

                    // check if the language is in our db 
                    logger_all.info("[select query request] : " + `SELECT * from master_language WHERE language_code = '${language}' AND language_status = 'Y'`)
                    const select_lang = await db.query(`SELECT * from master_language WHERE language_code = '${language}' AND language_status = 'Y'`);
                    logger_all.info("[select query response] : " + JSON.stringify(select_lang))

                    if (select_lang.length != 0) {

                        // get the whatsapp business id, bearer token for the sender number from db
                        logger_all.info("[insert query request] : " + `INSERT INTO message_template VALUES(NULL,${mobile_number[i].whatspp_config_id},'${unique_template_id}','${temp_name}',${select_lang[0].language_id},'${temp_category}','${JSON.stringify(temp_components)}','-','${user_id}','S',CURRENT_TIMESTAMP,'0000-00-00 00:00:00',${variable_count})`)
                        const insert_template = await db.query(`INSERT INTO message_template VALUES(NULL,${mobile_number[i].whatspp_config_id},'${unique_template_id}','${temp_name}',${select_lang[0].language_id},'${temp_category}','${JSON.stringify(temp_components)}','-','${user_id}','S',CURRENT_TIMESTAMP,'0000-00-00 00:00:00',${variable_count})`);
                        logger_all.info("[insert query response] : " + JSON.stringify(insert_template))

                        temp_lang = select_lang[0].language_id;
                        temp_insert_ids.push(insert_template.insertId)
                        sender_number.push(mobile_number[i].mobile_no)
                        sender_number_business_id.push(mobile_number[i].whatsapp_business_acc_id)
                        sender_number_bearer_token.push(mobile_number[i].bearer_token)
                    }
                    else {
                        logger_all.info("[template approval failed number] : " + mobile_number[i] + " - language not available in DB")
                        error_array.push({ mobile_number: mobile_number[i].mobile_no, reason: 'Language not available' })

                    }
                }

                // if no sender_number found send error response to the client
                if (sender_number.length == 0) {
                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'No number available or Language not available', request_id: req.body.request_id }))

                    var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'No number available or language not available' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    logger.silly("[update query request] : " + log_update);
                    const log_update_result = await db.query(log_update);
                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    res.json({ response_code: 0, response_status: 201, response_msg: 'No number available or Language not available', request_id: req.body.request_id });
                }
                else {
                    // if media is in template
                    if (media_url) {


                            // add media json block in components
                            temp_components.push({
                                "type": "HEADER",
                                "format": media_type
                            })

                            fs.unlinkSync(h_file[1]);
		  logger_all.info("[update query request] : " + `UPDATE message_template SET template_message = '${JSON.stringify(temp_components)}' WHERE unique_template_id = '${unique_template_id}'`)
                                        const update_succ = await db.query(`UPDATE message_template SET template_message = '${JSON.stringify(temp_components)}' WHERE unique_template_id = '${unique_template_id}'`);
                                        logger_all.info("[update query response] : " + JSON.stringify(update_succ))

		}
        /*                    // loop for the sender numbers in the user to request template for all sender numbers
                            for (var i = 0; i < sender_number.length; i++) {

                                // for (var l = 0; l < temp_lang.length; l++) {

                                // api url will have the sender number's whatsapp business acc id
                                api_url_updated = `${api_url}${sender_number_business_id[i]}/message_templates`

                                // json for request template
                                var data = {
                                    name: temp_name,
                                    language: language,
                                    category: temp_category,
                                    components: temp_components
                                };

                                var temp_msg = {
                                    method: 'post',
                                    url: api_url_updated,
                                    headers: {
                                        'Authorization': 'Bearer ' + sender_number_bearer_token[i],
                                    },
                                    params: data
                                };
                                // if(whtsap_send){
                                //   send_msg['data']['template']
                                // }

                                logger_all.info("[template approval request] : " + JSON.stringify(temp_msg))

                                await axios(temp_msg)
                                    .then(async function (response) {
                                        logger_all.info("[template approval success number] : " + sender_number[i] + " - " + util.inspect(response.data))
                                        // push the success template in succ_template
                                        succ_array.push({ mobile_number: sender_number[i], template_id: unique_template_id, template_name: temp_name })

                                        // if successfully requested, then update the template status and template id
                                        logger_all.info("[update query request] : " + `UPDATE message_template SET template_response_id = '${response.data.id}', template_status = 'S',template_message = '${JSON.stringify(temp_components)}' WHERE template_id = ${temp_insert_ids[i]}`)
                                        const update_succ = await db.query(`UPDATE message_template SET template_response_id = '${response.data.id}', template_status = 'S',template_message = '${JSON.stringify(temp_components)}' WHERE template_id = ${temp_insert_ids[i]}`);
                                        logger_all.info("[update query response] : " + JSON.stringify(update_succ))

                                        // increment the counter
                                        count++;
                                        // check if this is the last sender number, so we can send response to client
                                        if (count == sender_number.length) {
                                            res_send();
                                        }

                                    })
                                    .catch(async function (error) {
                                        logger_all.info("[template approval failed number] : " + sender_number[i] + " - " + error)

                                        // push the failed template in failed_template array
                                        error_array.push({ mobile_number: sender_number[i], reason: error.message })

                                        // if any error or failure, update the template status as F
                                        logger_all.info("[update query request] : " + `UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[i]}`)
                                        const update_fail = await db.query(`UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[i]}`);
                                        logger_all.info("[update query response] : " + JSON.stringify(update_fail))

                                        // increment the counter
                                        count++;
                                        // check if this is the last sender number, so we can send response to client
                                        if (count == sender_number.length) {
                                            res_send();
                                        }
                                    })
                                // }

                            }
                            // if got error when get header_handle, all template request will fail. push the all number in failed_array
                            if (error !== null) {
                                logger_all.info("[upload file failed number] : " + error)

                                for (var f = 0; f < temp_insert_ids; f++) {
                                    logger_all.info("[update query request] : " + `UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[f]}`)
                                    const update_fail = await db.query(`UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[f]}`);
                                    logger_all.info("[update query response] : " + JSON.stringify(update_fail))
                                    error_array.push({ mobile_number: sender_number[f], reason: 'Image upload failed' })

                                }

                                // if (count == sender_number.length) {
                                res_send();
                                // }
                            }

                        });

                    }
                    // if media is not in template
                   else {

                        // loop for the sender numbers in the user to request template for all sender numbers
                        for (var i = 0; i < sender_number.length; i++) {

                            // api url will have the sender number's whatsapp business acc id
                            api_url_updated = `${api_url}${sender_number_business_id[i]}/message_templates`

                            // json for request template
                            var data = {
                                name: temp_name,
                                language: language,
                                category: temp_category,
                                components: temp_components
                            };

                            var temp_msg = {
                                method: 'post',
                                url: api_url_updated,
                                headers: {
                                    'Authorization': 'Bearer ' + sender_number_bearer_token[i],
                                },
                                params: data
                            };

                            logger_all.info("[template approval request] : " + JSON.stringify(temp_msg))

                            await axios(temp_msg)
                                .then(async function (response) {
                                    logger_all.info("[template approval success number] : " + sender_number[i] + " - " + util.inspect(response.data))
                                    // push the success template in succ_template
                                    succ_array.push({ mobile_number: sender_number[i], template_id: unique_template_id, template_name: temp_name })

                                    // if successfully requested, then update the template status and template id
                                    logger_all.info("[update query request] : " + `UPDATE message_template SET template_response_id = '${response.data.id}', template_status = 'S',template_message = '${JSON.stringify(temp_components)}' WHERE template_id = ${temp_insert_ids[i]}`)
                                    const update_succ = await db.query(`UPDATE message_template SET template_response_id = '${response.data.id}', template_status = 'S',template_message = '${JSON.stringify(temp_components)}' WHERE template_id = ${temp_insert_ids[i]}`);
                                    logger_all.info("[update query response] : " + JSON.stringify(update_succ))

                                    // increment the counter
                                    count++
                                    // check if this is the last sender number, so we can send response to client
                                    if (count == sender_number.length) {
                                        res_send()
                                    }
                                })
                                .catch(async function (error) {
                                    logger_all.info("[template approval failed number] : " + sender_number[i] + " - " + error)
                                    // push the failed template in failed_template array
                                    error_array.push({ mobile_number: sender_number[i], reason: error.message })

                                    // if any error or failure, update the template status as F
                                    logger_all.info("[update query request] : " + `UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[i]}`)
                                    const update_fail = await db.query(`UPDATE message_template SET template_status = 'F' WHERE template_id = ${temp_insert_ids[i]}`);
                                    logger_all.info("[update query response] : " + JSON.stringify(update_fail))

                                    // increment the counter
                                    count++
                                    // check if this is the last sender number, so we can send response to client
                                    if (count == sender_number.length) {
                                        res_send()
                                    }
                                })
                            // }

                        }
                    }*/
			res_send();
                }
            }
            // function to send response to the client
            async function res_send() {
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success ', success: succ_array, failure: error_array, request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                res.json({ response_code: 1, response_status: 200, response_msg: 'Success ', success: succ_array, failure: error_array, request_id: req.body.request_id });
            }
        }
        catch (e) {
            logger_all.info(e);
            // if error occurred send error response to the client
            logger_all.info("[template approval failed response] : " + e)
            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Error occurred ', success: succ_array, failure: error_array, request_id: req.body.request_id }))

            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Error occurred' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
            logger.silly("[update query request] : " + log_update);
            const log_update_result = await db.query(log_update);
            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

            res.json({ response_code: 0, response_status: 201, response_msg: 'Error occurred ', success: succ_array, failure: error_array, request_id: req.body.request_id });

        }
    });

// api for to send compose_whatsapp_message
app.post("/compose_whatsapp_message", validator.body(composeMsgValidation),
    valid_user, async (req, res) => {
        try {

            var header_json = req.headers;
            let ip_address = header_json['x-forwarded-for'];

            // get all the req data
            var senders = req.body.sender_numbers;
            var mobiles = req.body.receiver_numbers;
            var api_bearer = req.headers.authorization;
            var whtsap_send = req.body.components;
            var template_id = req.body.template_id;
            var tmpl_name;
            var tmpl_lang;
            var body_variable = req.body.variable_values;

            // declare and initialize all the required variables and array
            var sender_numbers = {};
            var notready_numbers = [];
            var api_url_updated;
            var error_array = [];
            var user_id;
            var store_id;
            var full_short_name;
            var user_master;
            var sender_numbers_array = [];

            // logger.info(" [send_msg query parameters] : " + JSON.stringify(req.body));

            const insert_api_log = `INSERT INTO api_log VALUES(NULL,'${req.originalUrl}','${ip_address}','${req.body.request_id}','N','-','0000-00-00 00:00:00','Y',CURRENT_TIMESTAMP)`
            logger_all.info("[insert query request] : " + insert_api_log);
            const insert_api_log_result = await db.query(insert_api_log);
            logger_all.info("[insert query response] : " + JSON.stringify(insert_api_log_result))

            const check_req_id = `SELECT * FROM api_log WHERE request_id = '${req.body.request_id}' AND response_status != 'N' AND log_status='Y'`
            logger_all.info("[select query request] : " + check_req_id);
            const check_req_id_result = await db.query(check_req_id);
            logger_all.info("[select query response] : " + JSON.stringify(check_req_id_result));

            if (check_req_id_result.length != 0) {

                logger_all.info("[failed response] : Request already processed");
                logger.info("[API RESPONSE] " + JSON.stringify({ request_id: req.body.request_id, response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Request already processed' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id });

            }

            // get the available creidts of the user
            logger_all.info("[select query request] : " + `SELECT lim.available_messages,usr.user_id,usr.user_short_name,usr.parent_id FROM user_management usr
			LEFT JOIN message_limit lim ON lim.user_id = usr.user_id
			WHERE usr.bearer_token = '${api_bearer}' AND usr.usr_mgt_status = 'Y'`)

            const check_available_credits = await db.query(`SELECT lim.available_messages,usr.user_id,usr.user_short_name,usr.parent_id FROM user_management usr
			LEFT JOIN message_limit lim ON lim.user_id = usr.user_id
			WHERE usr.bearer_token = '${api_bearer}' AND usr.usr_mgt_status = 'Y'`);
            logger_all.info("[select query response] : " + JSON.stringify(check_available_credits))

            // if credits is less than numbers of message to send then process will continued otherwise send a error response to the client
            if (check_available_credits[0].available_messages < mobiles.length) {
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Available credit not enough.', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Available credit not enough' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'Available credit not enough.', request_id: req.body.request_id });
            }

            // get the user_id, user's parent id and user shortname to generate campaign name
            user_id = check_available_credits[0].user_id;
            user_master = check_available_credits[0].parent_id;
            var user_short_name = check_available_credits[0].user_short_name;

            // get the given user's master short name
            logger_all.info("[select query request] : " + `SELECT usr1.user_short_name FROM user_management usr
			LEFT JOIN user_management usr1 on usr.parent_id = usr1.user_id
			WHERE usr.user_short_name = '${user_short_name}'`)
            const get_user_short_name = await db.query(`SELECT usr1.user_short_name FROM user_management usr
			LEFT JOIN user_management usr1 on usr.parent_id = usr1.user_id
			WHERE usr.user_short_name = '${user_short_name}'`);
            logger_all.info("[select query response] : " + JSON.stringify(get_user_short_name))

            // if nothing returns set given user's short_name as full_short_name
            if (get_user_short_name.length == 0) {
                full_short_name = user_short_name;
            }
            else {
                // if the given user is primary admin then no master shouldn't be there. so set given user's short_name as full_short_name
                if (user_master == 1 || user_master == '1') {
                    full_short_name = user_short_name;
                }
                // concat the given user's master short_name in given user's short_name
                else {
                    full_short_name = `${get_user_short_name[0].user_short_name}_${user_short_name}`;
                }
            }

            // check if the template is available
            logger_all.info("[select query request] : " + `SELECT * FROM message_template tmp
			LEFT JOIN master_language lan ON lan.language_id = tmp.language_id
			WHERE tmp.unique_template_id = '${template_id}' AND tmp.template_status = 'Y'`)
            const check_variable_count = await db.query(`SELECT * FROM message_template tmp
			LEFT JOIN master_language lan ON lan.language_id = tmp.language_id
			WHERE tmp.unique_template_id = '${template_id}' AND tmp.template_status = 'Y'`);
            logger_all.info("[select query response] : " + JSON.stringify(check_variable_count))

            // if template not available send error response to the client
            if (check_variable_count.length == 0) {
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'template not available', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Template not available' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'template not available', request_id: req.body.request_id });
            }
            // if template available process will be continued
            else {

		if(mobiles.length ==0){
		                return res.json({ response_code: 0, response_status: 201, response_msg: 'Kinldy enter numbers', request_id: req.body.request_id });
		}

                // get the template name and language from template id
                tmpl_name = check_variable_count[0].template_name;
                tmpl_lang = check_variable_count[0].language_code;
                //var tmpl_message = JSON.parse(check_variable_count[0].template_message);

                /*try {
                    // get the template json from db to check the template has media and variables
                    var replced_message = check_variable_count[0].template_message.replace(/(\r\n|\n|\r)/gm, " ");
                    var tmpl_message = JSON.parse(replced_message);
                    // assign 0 and 1  value as 0 to check the media
                    var get_temp_details = [0, 0];

                    // loop the template json to check the template has media
                    for (var t = 0; t < tmpl_message.length; t++) {
                        // check if the template has image if yes set 2nd index value as i
                        if (tmpl_message[t].type.toLowerCase() == 'header' && tmpl_message[t].format.toLowerCase() == 'image') {
                            get_temp_details[2] = 'i';
                            get_temp_details[3] = 0;
                            get_temp_details[4] = 0;
                        }
                        // check if the template has video if yes set 3rd index value as v
                        else if (tmpl_message[t].type.toLowerCase() == 'header' && tmpl_message[t].format.toLowerCase() == 'video') {
                            get_temp_details[2] = 0;
                            get_temp_details[3] = 'v';
                            get_temp_details[4] = 0;
                        }
                        // check if the template has document if yes set 4th index value as d
                        else if (tmpl_message[t].type.toLowerCase() == 'header' && tmpl_message[t].format.toLowerCase() == 'document') {
                            get_temp_details[2] = 0;
                            get_temp_details[3] = 0;
                            get_temp_details[4] = 'd';
                        }
                        // if template doesn't have any media then set 2,3,4 index as 0
                        else {
                            get_temp_details[2] = 0;
                            get_temp_details[3] = 0;
                            get_temp_details[4] = 0;
                        }
                    }

                    if (get_temp_details.length != 0) {

                        // check if 2,3,4 is not 0. If these 3 index values are 0 then no media for this template
                        if (get_temp_details[2] != 0 || get_temp_details[3] != 0 || get_temp_details[4] != 0) {

                            // flag to check the request have media
                            var media_flag = false;

                            // loop the received json have media 
                            for (var p = 0; p < whtsap_send.length; p++) {
                                if (whtsap_send[p]['type'] == 'header' || whtsap_send[p]['type'] == 'HEADER') {
                                    // check the request have image
                                    if (get_temp_details[2] != 0) {
                                        if ((whtsap_send[p]['parameters'][0]['type'] == 'image' || whtsap_send[p]['parameters'][0]['type'] == 'IMAGE') && get_temp_details[2] != 0) {
                                            media_flag = true;
                                        }
                                        else { // Otherwise to send the  response message in 'Image required for this template' to the user
                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Image required for this template',request_id:req.body.request_id }))

                                            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Image required for this template' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                            logger.silly("[update query request] : " + log_update);
                                            const log_update_result = await db.query(log_update);
                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                            return res.json({ response_code: 0, response_status: 201, response_msg: 'Image required for this template' ,request_id:req.body.request_id});
                                        }
                                    }
                                    // check the request have video
                                    else if (get_temp_details[3] != 0) {

                                        if ((whtsap_send[p]['parameters'][0]['type'] == 'video' || whtsap_send[p]['parameters'][0]['type'] == 'VIDEO') && get_temp_details[3] != 0) {
                                            media_flag = true;
                                        }
                                        else {// Otherwise to send the  response message in 'Video required for this template' to the user
                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Video required for this template',request_id:req.body.request_id }))

                                            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Video required for this template' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                            logger.silly("[update query request] : " + log_update);
                                            const log_update_result = await db.query(log_update);
                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                            return res.json({ response_code: 0, response_status: 201, response_msg: 'Video required for this template' ,request_id:req.body.request_id});
                                        }
                                    }

                                    // check the request have document
                                    else if (get_temp_details[4] != 0) {
                                        if ((whtsap_send[p]['parameters'][0]['type'] == 'document' || whtsap_send[p]['parameters'][0]['type'] == 'DOCUMENT') && get_temp_details[4] != 0) {
                                            media_flag = true;
                                        }
                                        else {// Otherwise to send the  response message in 'Document required for this template' to the user
                                            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Document required for this template' ,request_id:req.body.request_id}))

                                            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Document required for this template' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                                            logger.silly("[update query request] : " + log_update);
                                            const log_update_result = await db.query(log_update);
                                            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                                            return res.json({ response_code: 0, response_status: 201, response_msg: 'Document required for this template' ,request_id:req.body.request_id});
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                catch (e) { // any error occurres send error response to client
                    logger_all.info("[media check error] : " + e)
                } */

                // check how many variables the template have
                // if (check_variable_count[0].body_variable_count != 0) {
                //     if (req.body.variable_values && body_variable.length != 0) {
                //         if (check_variable_count[0].body_variable_count == body_variable[0].length && body_variable.length == mobiles.length) {
                //         }
                //         else {
                //             logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Variable value mismatch.', request_id: req.body.request_id }))

                //             var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Variable value mismatch' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                //             logger.silly("[update query request] : " + log_update);
                //             const log_update_result = await db.query(log_update);
                //             logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                //             return res.json({ response_code: 0, response_status: 201, response_msg: 'Variable value mismatch.', request_id: req.body.request_id });
                //         }
                //     }
                //     else {
                //         logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Variable values required', request_id: req.body.request_id }))

                //         var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Variable values required' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                //         logger.silly("[update query request] : " + log_update);
                //         const log_update_result = await db.query(log_update);
                //         logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                //         return res.json({ response_code: 0, response_status: 201, response_msg: 'Variable values required', request_id: req.body.request_id });
                //     }
                // }

                // if request have store_id, store id will be received store id value
                if (req.body.store_id) {
                    store_id = req.body.store_id;
                }
                // otherwise store id will be 0
                else {
                    store_id = 0;
                }

                var msg_limit_for_sender = 0;
                // loop all the sender number's to get available credit
                for (var s = 0; s < senders.length; s++) {

                    // get all the sender number's available credit
                    logger_all.info("[select query request] : " + `SELECT con.phone_number_id,con.whatsapp_business_acc_id,con.bearer_token from whatsapp_config con
LEFT JOIN user_management usr ON usr.user_id = con.user_id
WHERE concat(con.country_code, con.mobile_no) = '${senders[s]}' AND con.whatspp_config_status = 'Y'`)
                    const select_details = await db.query(`SELECT con.phone_number_id,con.whatsapp_business_acc_id,con.bearer_token from whatsapp_config con
LEFT JOIN user_management usr ON usr.user_id = con.user_id
WHERE concat(con.country_code, con.mobile_no) = '${senders[s]}' AND con.whatspp_config_status = 'Y'`);
                    logger_all.info("[select query response] : " + JSON.stringify(select_details))

                    // check if the sender number have the template
                    if (select_details.length != 0) {

                        logger_all.info("[select query request] : " + `SELECT con.user_id,tmp.template_name,con.available_credit-con.sent_count available_credit FROM message_template tmp
					LEFT JOIN whatsapp_config con ON con.whatspp_config_id = tmp.whatsapp_config_id
					LEFT JOIN master_language lan ON lan.language_id = tmp.language_id
					WHERE tmp.template_name = '${tmpl_name}' AND tmp.template_status = 'Y' AND concat(con.country_code, con.mobile_no) = '${senders[s]}' AND lan.language_code = '${tmpl_lang}'`)
                        const check_template = await db.query(`SELECT con.user_id,tmp.template_name,con.available_credit-con.sent_count available_credit FROM message_template tmp
					LEFT JOIN whatsapp_config con ON con.whatspp_config_id = tmp.whatsapp_config_id
					LEFT JOIN master_language lan ON lan.language_id = tmp.language_id
					WHERE tmp.template_name = '${tmpl_name}' AND tmp.template_status = 'Y' AND concat(con.country_code, con.mobile_no) = '${senders[s]}' AND lan.language_code = '${tmpl_lang}'`);
                        logger_all.info("[select query response] : " + JSON.stringify(check_template))

                        // if template not available push the sender number in notready_numbers array.
                        if (check_template.length == 0) {
                            notready_numbers.push({ sender_number: senders[s], reason: 'Template not available for this number.' })

                        }
                        // otherwise process will be continued. Add the available sender_numbers in array
                        else {
                            msg_limit_for_sender = msg_limit_for_sender + check_template[0].available_credit

                            //logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET whatspp_config_status = 'P' WHERE concat(country_code, mobile_no) = '${senders[s]}' AND whatspp_config_status != 'D'`)
                            //const update_number = await db.query(`UPDATE whatsapp_config SET whatspp_config_status = 'P' WHERE concat(country_code, mobile_no) = '${senders[s]}' AND whatspp_config_status != 'D'`);
                            //logger_all.info("[update query response] : " + JSON.stringify(update_number))

                            sender_numbers_array.push(senders[s])
                            sender_numbers[senders[s]] = ({ user_id: check_template[0].user_id, count: check_template[0].available_credit, phone_number_id: select_details[0].phone_number_id, whatsapp_business_acc_id: select_details[0].whatsapp_business_acc_id, bearer_token: select_details[0].bearer_token })
                        }
                    }
                    // if sender_number not available push the sender number in notready_numbers array.
                    else {
                        notready_numbers.push({ sender_number: senders[s], reason: 'Number not available.' })
                    }

                }

                // if the sender_number json have no values then no sender number available. then send error response to the client.
                if (Object.keys(sender_numbers).length == 0) {
                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'No sender available', data: notready_numbers, request_id: req.body.request_id }))

                    var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'No sender available' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    logger.silly("[update query request] : " + log_update);
                    const log_update_result = await db.query(log_update);
                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    res.json({ response_code: 0, response_status: 201, response_msg: 'No sender available', data: notready_numbers, request_id: req.body.request_id });
                }
                else {

                    // check the limits and messages count
                    // if (msg_limit_for_sender < mobiles.length) {
                    //     logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Not sufficient credits.', request_id: req.body.request_id }))

                    //     var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Not sufficient credits' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    //     logger.silly("[update query request] : " + log_update);
                    //     const log_update_result = await db.query(log_update);
                    //     logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    //     return res.json({ response_code: 0, response_status: 201, response_msg: 'Not sufficient credits.', request_id: req.body.request_id });
                    // }
                    // get today's julian date to generate compose_unique_name
                    Date.prototype.julianDate = function () {
                        var j = parseInt((this.getTime() - new Date('Dec 30,' + (this.getFullYear() - 1) + ' 23:00:00').getTime()) / 86400000).toString(),
                            i = 3 - j.length;
                        while (i-- > 0) j = 0 + j;
                        return j
                    };

                    // declare db name and tables_name
                    var db_name = `whatsapp_messenger_${user_id}`;
                    var table_names = [`compose_whatsapp_tmpl_${user_id}`, `compose_whatsapp_status_tmpl_${user_id}`, `whatsapp_text_${user_id}`];
                    var compose_whatsapp_id;
                    var compose_unique_name;

                    logger_all.info("[select query request] : " + `SELECT compose_whatsapp_id from ${table_names[0]} ORDER BY compose_whatsapp_id desc limit 1`)
                    const select_compose_id = await dynamic_db.query(`SELECT compose_whatsapp_id from ${table_names[0]} ORDER BY compose_whatsapp_id desc limit 1`, null, `${db_name}`);
                    logger_all.info("[select query response] : " + JSON.stringify(select_compose_id))
                    // To select the select_compose_id length is '0' to create the compose unique name 
                    if (select_compose_id.length == 0) {
                        compose_unique_name = `ca_${full_short_name}_${new Date().julianDate()}_1`;
                    }

                    else { // Otherwise to get the select_compose_id using
                        compose_unique_name = `ca_${full_short_name}_${new Date().julianDate()}_${select_compose_id[0].compose_whatsapp_id + 1}`;
                    }
                    // To insert the tempalate details.
                    logger_all.info("[insert query request] : " + `INSERT INTO ${table_names[0]} VALUES(NULL,${user_id},${store_id},1,'${mobiles}','${senders}','${tmpl_name}','TEXT',${mobiles.length},1,${mobiles.length},'${compose_unique_name}','Y',CURRENT_TIMESTAMP)`)
                    const insert_compose = await dynamic_db.query(`INSERT INTO ${table_names[0]} VALUES(NULL,${user_id},${store_id},1,'${mobiles}','${senders}','${tmpl_name}','TEXT',${mobiles.length},1,${mobiles.length},'${compose_unique_name}','Y',CURRENT_TIMESTAMP)`, null, `${db_name}`);
                    logger_all.info("[insert query response] : " + JSON.stringify(insert_compose))
                    // To get the compose insert id.
                    compose_whatsapp_id = insert_compose.insertId;
                    // to the response message is send to client initiated 
                    logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Initiated', compose_id: compose_unique_name, available_senders: sender_numbers_array, not_available_senders: notready_numbers, request_id: req.body.request_id }))

                    var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                    logger.silly("[update query request] : " + log_update);
                    const log_update_result = await db.query(log_update);
                    logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                    // logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET sent_count = sent_count+1 WHERE concat(country_code, mobile_no) = '${key}'`)
                    // const update_count = await db.query(`UPDATE whatsapp_config SET sent_count = sent_count+1 WHERE concat(country_code, mobile_no) = '${key}'`);
                    // logger_all.info("[update query response] : " + JSON.stringify(update_count))

                    logger_all.info("[update query request] : " + `UPDATE message_limit SET available_messages = available_messages - ${mobiles.length} WHERE user_id ='${user_id}'`)
                    const update_limit = await db.query(`UPDATE message_limit SET available_messages = available_messages - ${mobiles.length} WHERE user_id ='${user_id}'`);
                    logger_all.info("[update query response] : " + JSON.stringify(update_limit))

                    res.json({ response_code: 1, response_status: 200, response_msg: 'Initiated', compose_id: compose_unique_name, available_senders: sender_numbers_array, not_available_senders: notready_numbers, request_id: req.body.request_id });

                    // var insert_count = 1;
                    // var insert_query = `INSERT INTO ${table_names[1]} VALUES`;
                    // the looping condition is true to continue the process and insert the table names and values
                    // for (var i = 0; i < mobiles.length; i++) {

                    // 	insert_query = insert_query + "" + `(NULL,${compose_whatsapp_id},NULL,'${mobiles[i]}','-','Y',CURRENT_TIMESTAMP,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),`;

                    // 	if (insert_count == 1000) {
                    // 		insert_query = insert_query.substring(0, insert_query.length - 1)

                    // 		logger_all.info(insert_query);
                    // 		const insert_mobile_numbers = await dynamic_db.query(insert_query, null, `${db_name}`);
                    // 		logger_all.info(" [insert query response] : " + JSON.stringify(insert_mobile_numbers))

                    // 		insert_count = 0;
                    // 		insert_query = `INSERT INTO ${table_names[1]} VALUES`;

                    // 	}
                    // 	insert_count = insert_count + 1;

                    // }
                    // insert_query = insert_query.substring(0, insert_query.length - 1)
                    // // to connect the db and insert insert_mobile_numbers details and to update the  available_messages is available_messages - to the mobile number length
                    // logger_all.info(insert_query);
                    // const insert_mobile_numbers = await dynamic_db.query(insert_query, null, `${db_name}`);
                    // logger_all.info(" [insert query response] : " + JSON.stringify(insert_mobile_numbers))

                    // // looping condition is true continue the process .to check the mobiles length is validated.
                    // for (var m = 0; m < mobiles.length; m) {
                    // 	// loop with in loop key var using in sender numbers 
                    // 	for (var key in sender_numbers) {
                    // 		if (sender_numbers[key].count >= 1) {

                    // 			api_url_updated = `${api_url}${sender_numbers[key].phone_number_id}/messages`

                    // 			var data;
                    // 			// body variable condition
                    // 			if (body_variable) {
                    // 				// looping condition is true continue the process .to check the whtsap_send length is validated.
                    // 				for (var p = 0; p < whtsap_send.length; p++) {
                    // 					if (whtsap_send[p]['type'] == 'body' || whtsap_send[p]['type'] == 'BODY') {
                    // 						whtsap_send.splice(p, 1); // 2nd parameter means remove one item only
                    // 					}
                    // 				}
                    // 				var variable_array = [];
                    // 				// looping condition is true continue the process .to check the body_variable length is validated.
                    // 				for (var p = 0; p < body_variable[m].length; p++) {
                    // 					variable_array.push({
                    // 						"type": "text",
                    // 						"text": body_variable[m][p]
                    // 					})
                    // 				}

                    // 				whtsap_send.push({
                    // 					"type": "body",
                    // 					"parameters": variable_array
                    // 				})

                    // 			}
                    // 			// whtsap_send length is not equal to '0' to get the valaue in data
                    // 			if (whtsap_send.length != 0) {
                    // 				data = JSON.stringify({
                    // 					"messaging_product": "whatsapp",
                    // 					"to": mobiles[m].toString(),
                    // 					"type": "template",
                    // 					"template": {
                    // 						"name": tmpl_name,
                    // 						"language": {
                    // 							"code": tmpl_lang
                    // 						},
                    // 						"components": whtsap_send
                    // 					}
                    // 				});
                    // 			}

                    // 			else {
                    // 				// otherwise to get the details in the value name is data
                    // 				data = JSON.stringify({
                    // 					"messaging_product": "whatsapp",
                    // 					"to": mobiles[m].toString(),
                    // 					"type": "template",
                    // 					"template": {
                    // 						"name": tmpl_name,
                    // 						"language": {
                    // 							"code": tmpl_lang
                    // 						}
                    // 					}
                    // 				});
                    // 			}
                    // 			// send msg value initiated .
                    // 			var send_msg = {
                    // 				method: 'post',
                    // 				url: api_url_updated,
                    // 				headers: {
                    // 					'Authorization': 'Bearer ' + sender_numbers[key].bearer_token,
                    // 					'Content-Type': 'application/json'
                    // 				},
                    // 				data: data
                    // 			};

                    // 			logger_all.info("[send msg request] : " + JSON.stringify(send_msg))
                    // 			// send_msg function 
                    // 			await axios(send_msg)
                    // 				.then(async function (response) {
                    // 					logger_all.info("[send msg response] : " + util.inspect(response));

                    // 					if (response.status == 200) {
                    // 						// to update the response_date,response_status,response_message,response_id in the particular table
                    // 						logger_all.info("[update query request] : " + `UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'S',response_message = 'SUCCESS',response_id = '${response.data.messages[0].id}',comments='${key}' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`)
                    // 						const update_success = await dynamic_db.query(`UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'S',response_message = 'SUCCESS',response_id = '${response.data.messages[0].id}',comments='${key}' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_success))
                    // 						// to update the whatsapp_config in the sent_count
                    // 						logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET sent_count = sent_count+1 WHERE concat(country_code, mobile_no) = '${key}'`)
                    // 						const update_count = await db.query(`UPDATE whatsapp_config SET sent_count = sent_count+1 WHERE concat(country_code, mobile_no) = '${key}'`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_count))

                    // 						logger_all.info("[update query request] : " + `UPDATE message_limit SET available_messages = available_messages - 1 WHERE user_id ='${sender_numbers[key].user_id}'`)
                    // 						const update_limit = await db.query(`UPDATE message_limit SET available_messages = available_messages - 1 WHERE user_id ='${sender_numbers[key].user_id}'`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_limit))

                    // 						sender_numbers[key].count = sender_numbers[key].count - 1;

                    // 					}
                    // 					else {
                    // 						// to update the response_date,response_status,response_message,response_id in the particular table
                    // 						logger_all.info("[update query request] : " + `UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'F',response_message = 'FAILED' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`)
                    // 						const update_fail = await dynamic_db.query(`UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'F',response_message = 'FAILED' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_fail))

                    // 					}

                    // 					m++;

                    // 					if (m == mobiles.length) {

                    // 						var com_status = 'S';
                    // 						if (error_array.length == 0) {
                    // 							com_status = 'S';
                    // 						}
                    // 						else {
                    // 							com_status = 'F';
                    // 						}
                    // 						// to update the whatsapp_status 'S' or 'F' To set in the table names.
                    // 						logger_all.info("[update query request] : " + `UPDATE ${table_names[0]} SET whatsapp_status = '${com_status}' WHERE compose_whatsapp_id = ${compose_whatsapp_id}`)
                    // 						const update_complete = await dynamic_db.query(`UPDATE ${table_names[0]} SET whatsapp_status = '${com_status}' WHERE compose_whatsapp_id = ${compose_whatsapp_id}`, null, `${db_name}`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_complete))
                    // 						// looping condition is true continue the process .to check the sender_numbers_array length is validated.

                    // 						/*for (var k = 0; k < sender_numbers_array.length; k++) {
                    // 							// to update the whatsapp_config in the whatspp_config_status = 'Y'
                    // 							logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`)
                    // 							const update_status = await db.query(`UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`);
                    // 							logger_all.info("[update query response] : " + JSON.stringify(update_status))
                    // 						}*/

                    // 					}

                    // 				})
                    // 				// any error occurres send error response to client and to update the getting details
                    // 				.catch(async function (error) {
                    // 					logger_all.info("[send msg failed response] : " + error);

                    // 					error_array.push(mobiles[m])

                    // 					logger_all.info("[update query request] : " + `UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'F',response_message = '${error.message}' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`)
                    // 					const update_failure = await dynamic_db.query(`UPDATE ${table_names[1]} SET response_date = CURRENT_TIMESTAMP,response_status = 'F',response_message = '${error.message}' WHERE compose_whatsapp_id = ${compose_whatsapp_id} AND mobile_no = '${mobiles[m]}'`, null, `${db_name}`);
                    // 					logger_all.info("[update query response] : " + JSON.stringify(update_failure))

                    // 					m++;

                    // 					if (m == mobiles.length) {
                    // 						var com_status = 'F';
                    // 						// to update the whatsapp_status 'F' To set in the table names.
                    // 						logger_all.info("[update query request] : " + `UPDATE ${table_names[0]} SET whatsapp_status = '${com_status}' WHERE compose_whatsapp_id = ${compose_whatsapp_id}`)
                    // 						const update_complete = await dynamic_db.query(`UPDATE ${table_names[0]} SET whatsapp_status = '${com_status}' WHERE compose_whatsapp_id = ${compose_whatsapp_id}`, null, `${db_name}`);
                    // 						logger_all.info("[update query response] : " + JSON.stringify(update_complete))
                    // 						// looping condition is true continue the process .to check the sender_numbers_array length is validated.
                    // 						/*for (var k = 0; k < sender_numbers_array.length; k++) {
                    // 							// to update the user details in the whatsapp_config 
                    // 							logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`)
                    // 							const update_status = await db.query(`UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`);
                    // 							logger_all.info("[update query response] : " + JSON.stringify(update_status))
                    // 						}*/

                    // 					}
                    // 				});
                    // 		}
                    // 		else {
                    // 			// sender_numbers.
                    // 		}

                    // 		if (m == mobiles.length) {
                    // 			break;
                    // 		}
                    // 	}
                    // }
                }
            }
        }
        catch (e) {// any error occurres send error response to client
            logger_all.info("[Send msg failed response] : " + e)
            // loop ing condition is true to continue the process and to update the  whatspp_config_status is 'Y'
            /*for (var k = 0; k < sender_numbers_array.length; k++) {
                logger_all.info("[update query request] : " + `UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`);
                const update_status = await db.query(`UPDATE whatsapp_config SET whatspp_config_status = 'Y' WHERE concat(country_code, mobile_no) = '${sender_numbers_array[k]}' AND whatspp_config_status != 'D'`);
                logger_all.info("[update query response] : " + JSON.stringify(update_status))
            }*/
        }
    });

// Api for webhook function
app.get('/webhook', function (req, res) {
    if (   //  Get all the req header data
        req.query['hub.mode'] == 'subscribe' &&
        req.query['hub.verify_token'] == 'yeejai123'
    ) { // To send message 
        res.send(req.query['hub.challenge']);
    } else { // any error occured
        res.sendStatus(400);
    }
});
// emojiUnicode function 
function emojiUnicode(emoji) {
    var comp;
    if (emoji.length === 1) {
        comp = emoji.charCodeAt(0);
    }
    comp = (
        (emoji.charCodeAt(0) - 0xD800) * 0x400
        + (emoji.charCodeAt(1) - 0xDC00) + 0x10000
    );
    if (comp < 0) {
        comp = emoji.charCodeAt(0);
    }
    return comp.toString("16");
};

// function to store media file in server
async function store_file(to, image_id, image_type) {
    // To check the mobile number to get the bearer_token
    logger_all.info("[select query request] : " + `SELECT bearer_token from whatsapp_config WHERE concat(country_code, mobile_no) = '${to}'`)
    const select_token = await db.query(`SELECT bearer_token from whatsapp_config WHERE concat(country_code, mobile_no) = '${to}'`);
    logger_all.info("[select query response] : " + JSON.stringify(select_token))
    var token = 'Bearer ' + select_token[0].bearer_token;
    // url method
    var get_url = {
        method: 'get',
        url: `${api_url}${image_id}`,
        headers: {
            'Authorization': token,
        }
    };
    logger_all.info("[get media request] : " + JSON.stringify(get_url));
    // to await the get_url function
    await axios(get_url)
        .then(async function (response) {

            logger_all.info("[get media response] : " + JSON.stringify(response.data))
            // To get the image 
            var get_image = {
                method: 'get',
                url: response.data.url,
                headers: {
                    'Authorization': token,
                },
                responseType: 'arraybuffer'
            };
            logger_all.info("[get media request] : " + JSON.stringify(get_image));
            // to await the get_image function
            await axios(get_image)
                .then(async function (response) {
                    // To write the file 
                    fs.writeFileSync(`${media_storage}/uploads/response_media/${image_id}.${image_type}`, response.data);
                    logger_all.info("[file write successful] : " + `${media_storage}/uploads/response_media/${image_id}.${image_type}`)

                })
                .catch(async function (error) { // any error occurres send error response to client
                    logger_all.info("[get media failed response] : " + error)
                })
        })
        .catch(async function (error) { // any error occurres send error response to client
            logger_all.info("[get media failed response] : " + error)
        })
}

app.post("/webhook", async function (request, response) {

    logger_all.info(' Incoming webhook: ' + JSON.stringify(request.body));

    try {

        var message = request.body;

        var json_leng = Object.keys(message.entry[0].changes[0].value).length;

        var webhook_type = message.entry[0].changes[0].field;

        if (webhook_type.toLowerCase() == 'phone_number_name_update' && json_leng == 4) {
            if (message.entry[0].changes[0].value.decision == 'APPROVED') {
                logger_all.info(" [update query request] : " + `UPDATE whatsapp_config SET available_credit = 900 WHERE concat(con.country_code, con.mobile_no) = '${message.entry[0].changes[0].value.display_phone_number}'`)
                const update_credits = await db.query(`UPDATE whatsapp_config SET available_credit = 900 WHERE concat(con.country_code, con.mobile_no) = '${message.entry[0].changes[0].value.display_phone_number}'`);
                logger_all.info(" [update query response] : " + JSON.stringify(update_credits))

            }
            else {
                logger_all.info(" [display name rejected] : " + message.entry[0].changes[0].value.decision + " - " + message.entry[0].changes[0].value.display_phone_number);

            }
        }

        if (webhook_type.toLowerCase() == 'messages' && json_leng == 3) {

            var send_number_with_cc = message.entry[0].changes[0].value.metadata.display_phone_number;
            var send_number = send_number_with_cc.substring(2);

            logger_all.info(" [select query request] : " + `SELECT mas.user_master_id, con.user_id from whatsapp_config con LEFT JOIN user_management man on man.user_id = con.user_id LEFT JOIN user_master mas on man.user_master_id = mas.user_master_id WHERE concat(con.country_code, con.mobile_no) = '${send_number_with_cc}'`)
            const select_usr_master = await db.query(`SELECT mas.user_master_id, con.user_id from whatsapp_config con LEFT JOIN user_management man on man.user_id = con.user_id LEFT JOIN user_master mas on man.user_master_id = mas.user_master_id WHERE concat(con.country_code, con.mobile_no)  = '${send_number_with_cc}'`);
            logger_all.info(" [select query response] : " + JSON.stringify(select_usr_master))

            if (select_usr_master.length != 0) {

                var id_array = [];

                if (select_usr_master[0].user_master_id == 1) {

                    logger_all.info(" [select query request] : " + `SELECT pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management pri
          where pri.user_id = ${select_usr_master[0].user_id}`)
                    const check_usr_type = await db.query(`SELECT pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management pri
          where pri.user_id = ${select_usr_master[0].user_id}`);
                    logger_all.info(" [select query response] : " + JSON.stringify(check_usr_type))

                    id_array.push(check_usr_type[0].primary_admin_id)

                }
                else if (select_usr_master[0].user_master_id == 2) {

                    logger_all.info(" [select query request] : " + `SELECT adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management adm
          left join user_management pri on adm.parent_id = pri.user_id
          where adm.user_id = ${select_usr_master[0].user_id}`)
                    const check_usr_type = await db.query(`SELECT adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management adm
          left join user_management pri on adm.parent_id = pri.user_id
          where adm.user_id = ${select_usr_master[0].user_id}`);
                    logger_all.info(" [select query response] : " + JSON.stringify(check_usr_type))

                    id_array.push(check_usr_type[0].admin_id)
                    id_array.push(check_usr_type[0].primary_admin_id)

                }
                else if (select_usr_master[0].user_master_id == 3) {

                    logger_all.info(" [select query request] : " + `SELECT dep.user_id dept_head_id, dep.user_name dept_head_name, adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management dep
          left join user_management adm on dep.parent_id = adm.user_id
          left join user_management pri on adm.parent_id = pri.user_id
          where dep.user_id = ${select_usr_master[0].user_id}`)
                    const check_usr_type = await db.query(`SELECT dep.user_id dept_head_id, dep.user_name dept_head_name, adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management dep
          left join user_management adm on dep.parent_id = adm.user_id
          left join user_management pri on adm.parent_id = pri.user_id
          where dep.user_id = ${select_usr_master[0].user_id}`);
                    logger_all.info(" [select query response] : " + JSON.stringify(check_usr_type))

                    id_array.push(check_usr_type[0].dept_head_id)
                    id_array.push(check_usr_type[0].admin_id)
                    id_array.push(check_usr_type[0].primary_admin_id)

                }
                else if (select_usr_master[0].user_master_id == 4) {

                    logger_all.info(" [select query request] : " + `SELECT usr.user_id, dep.user_id dept_head_id, dep.user_name dept_head_name, adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management usr
          left join user_management dep on usr.parent_id = dep.user_id
          left join user_management adm on dep.parent_id = adm.user_id
          left join user_management pri on adm.parent_id = pri.user_id
          where usr.user_id = ${select_usr_master[0].user_id}`)
                    const check_usr_type = await db.query(`SELECT usr.user_id, dep/web.user_id dept_head_id, dep.user_name dept_head_name, adm.user_id admin_id, adm.user_name admin_name, pri.user_id primary_admin_id, pri.user_name primary_admin_name 
          FROM user_management usr
          left join user_management dep on usr.parent_id = dep.user_id
          left join user_management adm on dep.parent_id = adm.user_id
          left join user_management pri on adm.parent_id = pri.user_id
          where usr.user_id = ${select_usr_master[0].user_id}`);
                    logger_all.info(" [select query response] : " + JSON.stringify(check_usr_type))

                    id_array.push(check_usr_type[0].user_id)
                    id_array.push(check_usr_type[0].dept_head_id)
                    id_array.push(check_usr_type[0].admin_id)
                    id_array.push(check_usr_type[0].primary_admin_id)

                }

                if (message.entry[0].changes[0].value.statuses[0].status == 'delivered') {

                    for (var t = 0; t < id_array.length; t++) {

                        logger_all.info(" [update query request] : " + `UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET delivery_status = 'Y',delivery_date = CURRENT_TIMESTAMP WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}' `, null, `whatsapp_messenger_${id_array[t]}`)
                        const update_msg_status_deli = await dynamic_db.query(`UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET delivery_status = 'Y',delivery_date = CURRENT_TIMESTAMP WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}'`, null, `whatsapp_messenger_${id_array[t]}`);
                        logger_all.info(" [update query response] : " + JSON.stringify(update_msg_status_deli))

                    }

                }

                if (message.entry[0].changes[0].value.statuses[0].status == 'failed') {

                    for (var t = 0; t < id_array.length; t++) {

                        logger_all.info(" [update query request] : " + `UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET response_status = 'F',response_message = 'Failed'  WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}'`)
                        const update_msg_status_fail = await dynamic_db.query(`UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET response_status = 'F',response_message = 'Failed'  WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}'`, null, `whatsapp_messenger_${id_array[t]}`)
                        logger_all.info(" [update query response] : " + JSON.stringify(update_msg_status_fail))

                        if (update_msg_status_fail.affectedRows != 0) {
                            logger_all.info(" [update query request] : " + `UPDATE message_limit SET available_messages = available_messages + 1 WHERE user_id ='${select_usr_master[0].user_id}'`)
                            const increase_limit = await db.query(`UPDATE message_limit SET available_messages = available_messages + 1 WHERE user_id ='${select_usr_master[0].user_id}'`);
                            logger_all.info(" [update query response] : " + JSON.stringify(increase_limit))

                            logger_all.info(" [update query request] : " + `UPDATE whatsapp_config SET sent_count = sent_count-1 WHERE concat(country_code, mobile_no) = '${send_number_with_cc}'`)
                            const update_count = await db.query(`UPDATE whatsapp_config SET sent_count = sent_count-1 WHERE concat(country_code, mobile_no) = '${send_number_with_cc}'`);
                            logger_all.info(" [update query response] : " + JSON.stringify(update_count))



                        }
                    }
                    logger_all.info(" [update query request] : " + `UPDATE messenger_response SET message_status = 'F' WHERE message_resp_id = '${message.entry[0].changes[0].value.statuses[0].id}'`)
                    const update_fail_reply = await db.query(`UPDATE messenger_response SET message_status = 'F' WHERE message_resp_id = '${message.entry[0].changes[0].value.statuses[0].id}'`)
                    logger_all.info(" [update query response] : " + JSON.stringify(update_fail_reply))

                }

                if (message.entry[0].changes[0].value.statuses[0].status == 'read') {

                    for (var t = 0; t < id_array.length; t++) {

                        logger_all.info(" [update query request] : " + `UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET read_status = 'Y',read_date = CURRENT_TIMESTAMP WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}' `, null, `whatsapp_messenger_${id_array[t]}`)
                        const update_msg_status_read = await dynamic_db.query(`UPDATE compose_whatsapp_status_tmpl_${id_array[t]} SET read_status = 'Y',read_date = CURRENT_TIMESTAMP WHERE response_id ='${message.entry[0].changes[0].value.statuses[0].id}'`, null, `whatsapp_messenger_${id_array[t]}`);
                        logger_all.info(" [update query response] : " + JSON.stringify(update_msg_status_read))
                    }
                    logger_all.info(" [update query request] : " + `UPDATE messenger_response SET message_is_read = 'Y', message_read_date = CURRENT_TIMESTAMP WHERE message_resp_id = '${message.entry[0].changes[0].value.statuses[0].id}'`)
                    const update_succ_reply = await db.query(`UPDATE messenger_response SET message_is_read = 'Y', message_read_date = CURRENT_TIMESTAMP WHERE message_resp_id = '${message.entry[0].changes[0].value.statuses[0].id}'`);
                    logger_all.info(" [update query response] : " + JSON.stringify(update_succ_reply))

                }

            }

        }

        if (webhook_type.toLowerCase() == 'message_template_status_update' && json_leng == 5) {

            var temp_status;

            if (message.entry[0].changes[0].value.event == 'APPROVED') {
                temp_status = 'Y';
            }
            else {
                temp_status = 'R'
            }

            logger_all.info(" [update query request] : " + `UPDATE message_template SET template_status = '${temp_status}',approve_date = CURRENT_TIMESTAMP WHERE template_response_id = '${message.entry[0].changes[0].value.message_template_id}'`)
            const update_template_approval = await db.query(`UPDATE message_template SET template_status = '${temp_status}',approve_date = CURRENT_TIMESTAMP WHERE template_response_id = '${message.entry[0].changes[0].value.message_template_id}'`);
            logger_all.info(" [update query response] : " + JSON.stringify(update_template_approval))

        }

        if (webhook_type.toLowerCase() == 'messages' && json_leng == 4) {

            var body_message;
            var socket_response = {};
            // request.io.emit("test", "message received");

            var from = message.entry[0].changes[0].value.messages[0].from;
            var to = message.entry[0].changes[0].value.metadata.display_phone_number;

            var profile_name_encode = message.entry[0].changes[0].value.contacts[0].profile.name;

            let buff_name = new Buffer(profile_name_encode);
            let profile_name = buff_name.toString('base64');
            var msg_id = message.entry[0].changes[0].value.messages[0].id;
            var msg_type = message.entry[0].changes[0].value.messages[0].type;
            var to_without_cc = to.substring(2);

            var get_msg;
            var interactive_msg_components;
            var device_list_array = [];

            logger_all.info(" [select query request] : " + `SELECT wc.wht_display_name,wc.phone_number_id,wc.bearer_token,wc.whatspp_config_id,wc.user_id,wc.store_id,wc.mobile_no,udl.device_token,usr.parent_id
from whatsapp_config wc inner JOIN user_device_list udl on wc.user_id = udl.device_user_id 
inner join user_management usr on usr.user_id = wc.user_id
where concat(wc.country_code, wc.mobile_no) = '${to}' AND wc.whatspp_config_status = 'Y'`);

            const select_usr_id = await db.query(`SELECT wc.wht_display_name,wc.phone_number_id,wc.bearer_token,wc.whatspp_config_id,wc.user_id,wc.store_id,wc.mobile_no,udl.device_token,usr.parent_id
from whatsapp_config wc inner JOIN user_device_list udl on wc.user_id = udl.device_user_id 
inner join user_management usr on usr.user_id = wc.user_id
where concat(wc.country_code, wc.mobile_no) = '${to}' AND wc.whatspp_config_status = 'Y'`);

            logger_all.info(" [select query response] : " + JSON.stringify(select_usr_id));

            if (select_usr_id.length != 0) {
                device_list_array.push(select_usr_id[0].device_token);

                if (select_usr_id[0].parent_id != select_usr_id[0].user_id) {

                    logger_all.info(" [select query request] : " + `SELECT * FROM user_device_list WHERE device_user_id = ${select_usr_id[0].parent_id} and user_device_status = 'Y'`);

                    const select_usr_master = await db.query(`SELECT * FROM user_device_list WHERE device_user_id = ${select_usr_id[0].parent_id} and user_device_status = 'Y'`);

                    logger_all.info(" [select query response] : " + JSON.stringify(select_usr_master));

                    if (select_usr_master.length != 0) {
                        device_list_array.push(select_usr_master[0].device_token);
                    }

                }

                switch (msg_type) {
                    case 'text':
                        body_message = message.entry[0].changes[0].value.messages[0].text.body;
                        //	var txt_msg = Buffer.from(message.entry[0].changes[0].value.messages[0].text.body).toString('base64');
                        get_msg = message.entry[0].changes[0].value.messages[0].text.body;

                        let buff = new Buffer(message.entry[0].changes[0].value.messages[0].text.body);
                        let txt_msg = buff.toString('base64');
                        message['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] = txt_msg

                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].text)}','${txt_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);

                        const insert_text = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].text)}','${txt_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_text));

                        socket_response = {
                            "from": from,
                            "to": to,
                            "msg_type": "text",
                            "msg": body_message,
                            "response_msg": 1
                        }
                        break;
                    case 'image':
                        get_msg = message.entry[0].changes[0].value.messages[0].image.id + ".jpg";

                        if (message.entry[0].changes[0].value.messages[0].image.caption) {
                            body_message = '🖼️ Image';
                            var caption = message.entry[0].changes[0].value.messages[0].image.caption;
                            let buff = new Buffer(message.entry[0].changes[0].value.messages[0].image.caption);
                            let img_msg = buff.toString('base64');
                            message['entry'][0]['changes'][0]['value']['messages'][0]['image']['caption'] = img_msg

                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].image)}',NULL,'${message.entry[0].changes[0].value.messages[0].image.id}.jpg','${message.entry[0].changes[0].value.messages[0].image.mime_type}','${img_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_img = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].image)}',NULL,'${message.entry[0].changes[0].value.messages[0].image.id}.jpg','${message.entry[0].changes[0].value.messages[0].image.mime_type}','${img_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_img))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "image",
                                "msg": message.entry[0].changes[0].value.messages[0].image.id + ".jpg",
                                "caption": caption,
                                "response_msg": 1
                            }

                        }
                        else {
                            body_message = '🖼️ Image';
                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].image)}',NULL,'${message.entry[0].changes[0].value.messages[0].image.id}.jpg','${message.entry[0].changes[0].value.messages[0].image.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_img = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].image)}',NULL,'${message.entry[0].changes[0].value.messages[0].image.id}.jpg','${message.entry[0].changes[0].value.messages[0].image.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_img))


                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "image",
                                "msg": message.entry[0].changes[0].value.messages[0].image.id + ".jpg",
                                "response_msg": 1
                            }

                        }
                        await store_file(to, message.entry[0].changes[0].value.messages[0].image.id, 'jpg')

                        break;
                    case 'video':

                        if (message.entry[0].changes[0].value.messages[0].video.caption) {
                            body_message = '▶️ Video';
                            var caption = message.entry[0].changes[0].value.messages[0].video.caption;
                            let buff = new Buffer(message.entry[0].changes[0].value.messages[0].video.caption);
                            let video_msg = buff.toString('base64');
                            message['entry'][0]['changes'][0]['value']['messages'][0]['video']['caption'] = video_msg

                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].video)}',NULL,'${message.entry[0].changes[0].value.messages[0].video.id}.mp4','${message.entry[0].changes[0].value.messages[0].video.mime_type}','${video_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_video = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].video)}',NULL,'${message.entry[0].changes[0].value.messages[0].video.id}.mp4','${message.entry[0].changes[0].value.messages[0].video.mime_type}','${video_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_video))
                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "video",
                                "msg": message.entry[0].changes[0].value.messages[0].video.id + ".mp4",
                                "caption": caption,
                                "response_msg": 1
                            }
                        }
                        else {
                            body_message = '▶️ Video';
                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].video)}',NULL,'${message.entry[0].changes[0].value.messages[0].video.id}.mp4','${message.entry[0].changes[0].value.messages[0].video.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_video = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].video)}',NULL,'${message.entry[0].changes[0].value.messages[0].video.id}.mp4','${message.entry[0].changes[0].value.messages[0].video.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_video))
                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "video",
                                "msg": message.entry[0].changes[0].value.messages[0].video.id + ".mp4",
                                "response_msg": 1
                            }
                        }

                        await store_file(to, message.entry[0].changes[0].value.messages[0].video.id, 'mp4')

                        break;
                    case 'document':
                        var format = message.entry[0].changes[0].value.messages[0].document.mime_type.split("/");

                        if (message.entry[0].changes[0].value.messages[0].document.caption) {
                            body_message = '📄 Document';
                            var caption = message.entry[0].changes[0].value.messages[0].document.caption;
                            let buff = new Buffer(message.entry[0].changes[0].value.messages[0].document.caption);
                            let doc_msg = buff.toString('base64');
                            message['entry'][0]['changes'][0]['value']['messages'][0]['document']['caption'] = doc_msg

                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].document)}',NULL,'${message.entry[0].changes[0].value.messages[0].document.id}.${format[1]}','${message.entry[0].changes[0].value.messages[0].document.mime_type}','${doc_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_document = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].document)}',NULL,'${message.entry[0].changes[0].value.messages[0].document.id}.${format[1]}','${message.entry[0].changes[0].value.messages[0].document.mime_type}','${doc_msg}',NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_document))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "document",
                                "msg": message.entry[0].changes[0].value.messages[0].document.id + "." + format[1],
                                "caption": caption,
                                "response_msg": 1
                            }
                        }
                        else {
                            body_message = '📄 Document';
                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].document)}',NULL,'${message.entry[0].changes[0].value.messages[0].document.id}.${format[1]}','${message.entry[0].changes[0].value.messages[0].document.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_document = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].document)}',NULL,'${message.entry[0].changes[0].value.messages[0].document.id}.${format[1]}','${message.entry[0].changes[0].value.messages[0].document.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_document))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "document",
                                "msg": message.entry[0].changes[0].value.messages[0].document.id + "." + format[1],
                                "response_msg": 1
                            }
                        }

                        await store_file(to, message.entry[0].changes[0].value.messages[0].document.id, format[1])
                        break;
                    case 'button':
                        body_message = message.entry[0].changes[0].value.messages[0].button.text;
                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].button)}',NULL,NULL,NULL,NULL,'${message.entry[0].changes[0].value.messages[0].button.text}',NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                        const insert_reply_btn = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].button)}',NULL,NULL,NULL,NULL,'${message.entry[0].changes[0].value.messages[0].button.text}',NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_reply_btn))

                        socket_response = {
                            "from": from,
                            "to": to,
                            "msg_type": "button",
                            "msg": body_message,
                            "response_msg": 1
                        }

                        //body_message = message.entry[0].changes[0].value.messages[0].button.text;
                        break;
                    case 'reaction':
                        if (Object.keys(message.entry[0].changes[0].value.messages[0].reaction).length != 1) {
                            body_message = message.entry[0].changes[0].value.messages[0].reaction.emoji;
                            var emoji_code = emojiUnicode(message.entry[0].changes[0].value.messages[0].reaction.emoji);
                            message['entry'][0]['changes'][0]['value']['messages'][0]['reaction']['emoji'] = emoji_code

                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].reaction)}',NULL,NULL,NULL,NULL,NULL,'${emoji_code}',NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_reaction = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].reaction)}',NULL,NULL,NULL,NULL,NULL,'${emoji_code}',NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_reaction))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "reaction",
                                "msg": body_message,
                                "response_msg": 1
                            }
                            //body_message = message.entry[0].changes[0].value.messages[0].reaction.emoji;
                        }
                        break;
                    case 'sticker':
                        body_message = '🫥 Sticker';
                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].sticker)}',NULL,'${message.entry[0].changes[0].value.messages[0].sticker.id}.webp','${message.entry[0].changes[0].value.messages[0].sticker.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                        const insert_sticker = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].sticker)}',NULL,'${message.entry[0].changes[0].value.messages[0].sticker.id}.webp','${message.entry[0].changes[0].value.messages[0].sticker.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_sticker))
                        await store_file(to, message.entry[0].changes[0].value.messages[0].sticker.id, 'webp')

                        socket_response = {
                            "from": from,
                            "to": to,
                            "msg_type": "sticker",
                            "msg": message.entry[0].changes[0].value.messages[0].sticker.id + ".webp",
                            "response_msg": 1
                        }
                        break;

                    case 'location':
                        body_message = 'Location';
                        get_msg = 'location';

                        //logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${prof$
                        //const insert_sticker = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id$
                        //logger_all.info(" [insert query response] : " + JSON.stringify(insert_sticker))
                        //await store_file(to, message.entry[0].changes[0].value.messages[0].sticker.id, 'webp')

                        /*socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "sticker",
                                "msg": message.entry[0].changes[0].value.messages[0].sticker.id + ".webp",
                                "response_msg": 1
                        }*/
                        break;

                    case 'audio':
                        body_message = '🎵 Audio';
                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].audio)}',NULL,'${message.entry[0].changes[0].value.messages[0].audio.id}.ogg','${message.entry[0].changes[0].value.messages[0].audio.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                        const insert_voice = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].audio)}',NULL,'${message.entry[0].changes[0].value.messages[0].audio.id}.ogg','${message.entry[0].changes[0].value.messages[0].audio.mime_type}',NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_voice))

                        await store_file(to, message.entry[0].changes[0].value.messages[0].audio.id, 'ogg')

                        socket_response = {
                            "from": from,
                            "to": to,
                            "msg_type": "audio",
                            "msg": message.entry[0].changes[0].value.messages[0].audio.id + ".ogg",
                            "response_msg": 1
                        }
                        break;

                    case 'interactive':

                        if (message.entry[0].changes[0].value.messages[0].interactive.type == 'list_reply') {
                            body_message = message.entry[0].changes[0].value.messages[0].interactive.list_reply.title;
                            var interactive_msg = message.entry[0].changes[0].value.messages[0].interactive.list_reply.title;
                            let buff_inter = new Buffer(message.entry[0].changes[0].value.messages[0].interactive.list_reply.title);
                            let interactive_msg_encode = buff_inter.toString('base64');
                            message['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply_title'] = interactive_msg_encode

                            get_msg = message.entry[0].changes[0].value.messages[0].interactive.list_reply.id;

                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].interactive)}','${interactive_msg_encode}',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_interaction = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].interactive)}','${interactive_msg_encode}',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_interaction))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "interactive",
                                "msg": body_message,
                                "response_msg": 1
                            }
                        }
                        if (message.entry[0].changes[0].value.messages[0].interactive.type == 'button_reply') {
                            get_msg = message.entry[0].changes[0].value.messages[0].interactive.button_reply.id;

                            body_message = message.entry[0].changes[0].value.messages[0].interactive.button_reply.title;
                            logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','button','${JSON.stringify(message.entry[0].changes[0].value.messages[0].interactive)}',NULL,NULL,NULL,NULL,'${message.entry[0].changes[0].value.messages[0].interactive.button_reply.title}',NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                            const insert_reply_btn = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','button','${JSON.stringify(message.entry[0].changes[0].value.messages[0].interactive)}',NULL,NULL,NULL,NULL,'${message.entry[0].changes[0].value.messages[0].interactive.button_reply.title}',NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                            logger_all.info(" [insert query response] : " + JSON.stringify(insert_reply_btn))

                            socket_response = {
                                "from": from,
                                "to": to,
                                "msg_type": "button",
                                "msg": "button",
                                "response_msg": 1
                            }
                        }
                        break;

                    case 'unknown':
                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].unknown)}','Unsupported format',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                        const insert_unsupport = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${to}','${from}','${profile_name}','${msg_id}','${msg_type}','${JSON.stringify(message.entry[0].changes[0].value.messages[0].unknown)}','Unsupported format',NULL,NULL,NULL,NULL,NULL,NULL,'N','Y',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_unsupport))

                        break;

                    default:
                        logger_all.info(" Unsupported format")
                }

                request.io.emit("messenger_response", socket_response);
                if (body_message) {

                    var data = JSON.stringify({
                        "registration_ids": device_list_array,
                        "notification": {
                            "title": from,
                            "body": body_message,
                            "content-available": true,
                            "priority": "high",
                            "body_loc_key": to

                        },
                        "data": {
                            "title": from,
                            "priority": "high",
                            "content-available": true,
                            "bodyText": body_message,
                            "body_loc_key": to
                        }
                    });

                    var config = {
                        method: 'post',
                        url: 'https://fcm.googleapis.com/fcm/send',
                        headers: {
                            'Authorization': process.env.NOTIFICATION_SERVER_KEY,
                            'Content-Type': 'application/json'
                        },
                        data: data
                    };

                    logger_all.info(data)


                    axios(config)
                        .then(function (response) {
                            logger_all.info(JSON.stringify(response.data));
                        })
                        .catch(function (error) {
                            logger_all.info(error);
                        });
                }
                if (get_msg) {

                    logger_all.info(" [select query request] : " + `SELECT * FROM master_flow WHERE whatsapp_config_id = '${select_usr_id[0].whatspp_config_id}' AND flow_status = 'Y'`)
                    var select_flow = await db.query(`SELECT * FROM master_flow WHERE whatsapp_config_id = '${select_usr_id[0].whatspp_config_id}' AND flow_status = 'Y'`);
                    logger_all.info(" [select query response] : " + JSON.stringify(select_flow))

                    if (select_flow.length != 0) {
                        var json_flow = JSON.parse(select_flow[0].flow_json)
                        var flow_id = select_flow[0].flow_id;
                        var message_array;

                        logger_all.info(" [select query request] : " + `SELECT * FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`)
                        var select_level = await db.query(`SELECT * FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                        logger_all.info(" [select query response] : " + JSON.stringify(select_level))

                        if (select_level.length == 0) {
                            for (var i = 0; i < json_flow.length; i++) {
                                if (json_flow[i].parent.includes('0')) {
                                    var pattern = eval(json_flow[i].pattern)
                                    var regex_check = pattern.test(get_msg.toLowerCase());

                                    if (regex_check == true && json_flow[i].type.includes(msg_type.toLowerCase())) {
                                        logger_all.info(" [insert query request] : " + `INSERT INTO flow_level_maintain VALUES(NULL,${flow_id},'${from}',${json_flow[i].id},0,'Y',CURRENT_TIMESTAMP)`)
                                        const insert_level = await db.query(`INSERT INTO flow_level_maintain VALUES(NULL,${flow_id},'${from}',${json_flow[i].id},0,'Y',CURRENT_TIMESTAMP)`);
                                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_level))
                                        message_array = json_flow[i].message;

                                        if (json_flow[i].hasOwnProperty("beforeReply")) {
                                            message_array = await eval(json_flow[i].beforeReply)(get_msg);
                                        }
                                        break;
                                    }
                                    else {
                                        message_array = json_flow[i].restart;
                                    }
                                }
                            }
                        }
                        else {

                            var flow_level = select_level[0].flow_level;
                            var check_flag = false;
                            var matched_id = 0;
                            for (var i = 0; i < json_flow.length; i++) {
                                if (json_flow[i].parent.includes('0')) {
                                    var pattern = eval(json_flow[i].pattern)
                                    var regex_check = pattern.test(get_msg.toLowerCase());

                                    if (regex_check == true && json_flow[i].type.includes(msg_type.toLowerCase())) {
                                        matched_id = i;
                                        check_flag = true;
                                        break;
                                    }
                                }
                            }
                            logger_all.info(check_flag)
                            if (check_flag == true) {

                                logger_all.info(" [delete query request] : " + `DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`)
                                const delete_level = await db.query(`DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                logger_all.info(" [delete query response] : " + JSON.stringify(delete_level))

                                logger_all.info(" [insert query request] : " + `INSERT INTO flow_level_maintain VALUES(NULL,${flow_id},'${from}',${json_flow[matched_id].id},0,'Y',CURRENT_TIMESTAMP)`)
                                const insert_level = await db.query(`INSERT INTO flow_level_maintain VALUES(NULL,${flow_id},'${from}',${json_flow[matched_id].id},0,'Y',CURRENT_TIMESTAMP)`);
                                logger_all.info(" [insert query response] : " + JSON.stringify(insert_level))
                                message_array = json_flow[matched_id].message;

                                if (json_flow[matched_id].hasOwnProperty("beforeReply")) {
                                    message_array = await eval(json_flow[matched_id].beforeReply)(get_msg);
                                }

                            }
                            else {
                                var now = +new Date();
                                var createdAt = +new Date(Date.parse(select_level[0].flm_ent_date.toString()));
                                var oneDay = 24 * 60 * 60 * 1000;

                                var restart_flag = true;
                                var flow_level_temp = flow_level

                                while (restart_flag) {
                                    for (var r = 0; r < json_flow.length; r++) {
                                        if (json_flow[r].id == flow_level_temp) {
                                            if (json_flow[r].parent.includes('0')) {
                                                restart_flag = false;
                                                flow_level_temp = r;
                                                break;
                                            }
                                            else {
                                                flow_level_temp = json_flow[r].parent
                                            }
                                        }
                                    }
                                }

                                if ((now - createdAt) > oneDay) {
                                    message_array = json_flow[flow_level_temp].restart;

                                    //const update_level_count = await db.query(`UPDATE flow_level_maintain SET flow_level = 0,flow_level_count = 0 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                    //logger_all.info(" [insert query response] : " + JSON.stringify(update_level_count))

                                    logger_all.info(" [delete query request] : " + `DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`)
                                    const delete_level = await db.query(`DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                    logger_all.info(" [delete query response] : " + JSON.stringify(delete_level))
                                }
                                else {

                                    var flag_check = false;
                                    for (var i = 0; i < json_flow.length; i++) {
                                        if (json_flow[i].parent == flow_level.toString()) {
                                            var pattern = eval(json_flow[i].pattern)
                                            var regex_check = pattern.test(get_msg.toLowerCase());

                                            if (select_level[0].flow_level_count < 3) {
                                                if (regex_check == true && json_flow[i].type.includes(msg_type.toLowerCase())) {

                                                    //if(select_level[0].flow_level_count < 3){
                                                    message_array = json_flow[i].message
                                                    var msg_updated_json = { valid_code: 1 };

                                                    if (json_flow[i].hasOwnProperty("beforeReply")) {
                                                        msg_updated_json = await eval(json_flow[i].beforeReply)(get_msg);
                                                        message_array = msg_updated_json.message;
                                                    }
                                                    const update_level_count = await db.query(`UPDATE flow_level_maintain SET flow_level_count = flow_level_count+1 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                                    logger_all.info(" [insert query response] : " + JSON.stringify(update_level_count))

                                                    if (msg_updated_json.valid_code == 1) {
                                                        const update_level = await db.query(`UPDATE flow_level_maintain SET flow_level = ${json_flow[i].id}, flow_level_count = 0 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                                        logger_all.info(" [insert query response] : " + JSON.stringify(update_level))

                                                    }
                                                    if (json_flow[i].hasOwnProperty("end")) {

                                                        //const update_level_count = await db.query(`UPDATE flow_level_maintain SET flow_level = 0,flow_level_count = 0 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                                        //logger_all.info(" [insert query response] : " + JSON.stringify(update_level_count))

                                                        logger_all.info(" [delete query request] : " + `DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`)
                                                        const delete_level = await db.query(`DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                                    }

                                                    flag_check = true;
                                                    break;
                                                }

                                                else {
                                                    if (json_flow[i].hasOwnProperty("invalid")) {
                                                        const update_level_count = await db.query(`UPDATE flow_level_maintain SET flow_level_count = flow_level_count+1 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                                        logger_all.info(" [insert query response] : " + JSON.stringify(update_level_count))

                                                        message_array = json_flow[i].invalid;
                                                        flag_check = true;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (flag_check == false) {

                                        message_array = json_flow[flow_level_temp].restart;

                                        //const update_level_count = await db.query(`UPDATE flow_level_maintain SET flow_level =0,flow_level_count = 0 WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                        //logger_all.info(" [insert query response] : " + JSON.stringify(update_level_count))

                                        logger_all.info(" [delete query request] : " + `DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`)
                                        const delete_level = await db.query(`DELETE FROM flow_level_maintain WHERE mobile_number = '${from}' AND flow_id = '${flow_id}'`);
                                        logger_all.info(" [delete query response] : " + JSON.stringify(delete_level))

                                    }
                                }
                            }
                        }

                        logger_all.info("[select query request] : " + `SELECT * from flow_level_entry_data WHERE mobile_number = '${from}'`)
                        const select_data = await db.query(`SELECT * from flow_level_entry_data WHERE mobile_number = '${from}'`);
                        logger_all.info("[select query response] : " + JSON.stringify(select_data))

                        if (select_data.length == 0) {

                            logger_all.info("[insert query request] : " + `INSERT INTO flow_level_entry_data VALUES(NULL,'${from}','${get_msg}',CURREnT_TIMESTAMP)`)
                            const update_data = await db.query(`INSERT INTO flow_level_entry_data VALUES(NULL,'${from}','${get_msg}',CURREnT_TIMESTAMP)`);
                            logger_all.info("[insert query response] : " + JSON.stringify(update_data))
                        }
                        else {
                            logger_all.info("[update query request] : " + `UPDATE flow_level_entry_data SET entry_data = '${get_msg}' WHERE mobile_number = '${from}'`)
                            const update_data = await db.query(`UPDATE flow_level_entry_data SET entry_data = '${get_msg}' WHERE mobile_number = '${from}'`);
                            logger_all.info("[update query response] : " + JSON.stringify(update_data))
                        }

                        logger_all.info(message_array)
                        for (var m = 0; m < message_array.length; m++) {

                            var msg_json = message_array[m];

                            var msg_json_type = msg_json.type;
                            var insert_bot_reply;

                            var buff_pro_name = new Buffer(select_usr_id[0].wht_display_name);
                            var pro_name = buff_pro_name.toString('base64');

                            logger_all.info(msg_json_type)
                            switch (msg_json_type) {
                                case "text":

                                    var buff_bot_reply = new Buffer(msg_json.text.body);
                                    var bot_msg = buff_bot_reply.toString('base64');

                                    logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}','${bot_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                                    insert_bot_reply = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}',"${bot_msg}",NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                                    logger_all.info(" [insert query response] : " + JSON.stringify(insert_bot_reply))

                                    break;
                                case "interactive":
                                    if (msg_json.interactive.type == 'button') {
                                        var buff_bot_reply = new Buffer(msg_json.interactive.body.text);
                                        var bot_msg = buff_bot_reply.toString('base64');

                                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}','${bot_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                                        insert_bot_reply = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}',"${bot_msg}",NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_bot_reply))

                                    }

                                    if (msg_json.interactive.type == 'list') {
                                        var html_list = "<ul>";

                                        for (var i = 0; i < msg_json.interactive.action.sections[0].rows.length; i++) {
                                            html_list = html_list + "<li>" + msg_json.interactive.action.sections[0].rows[i].title + "</li>"

                                        }

                                        html_list = html_list + "</ul>";

                                        logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','list','${JSON.stringify(msg_json)}','-',NULL,NULL,NULL,NULL,NULL,'${html_list}','N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                                        insert_bot_reply = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','list','${JSON.stringify(msg_json)}','-',NULL,NULL,NULL,NULL,NULL,'${html_list}','N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                                        logger_all.info(" [insert query response] : " + JSON.stringify(insert_bot_reply))

                                    }

                                    break;
                                case "template":
                                    logger_all.info(" [select query request] : " + `SELECT * FROM message_template WHERE template_name = '${msg_json.template.name}' AND template_status = 'Y' limit 1`)
                                    var select_template_data = await db.query(`SELECT * FROM message_template WHERE template_name = '${msg_json.template.name}' AND template_status = 'Y' limit 1`);
                                    logger_all.info(" [select query response] : " + JSON.stringify(select_template_data))

                                    if (select_template_data.length != 0) {
                                        var template_json = JSON.parse(select_template_data[0].template_message)


                                        for (var t = 0; t < template_json.length; t++) {
                                            if (template_json[t].type.toUpperCase() == 'BODY' && select_template_data[0].body_variable_count == 0) {
                                                var buff_bot_reply = new Buffer(template_json[t].text);
                                                var bot_msg = buff_bot_reply.toString('base64');

                                                logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}','${bot_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                                                insert_bot_reply = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}',"${bot_msg}",NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                                                logger_all.info(" [insert query response] : " + JSON.stringify(insert_bot_reply))
                                            }


                                            if (template_json[t].type.toUpperCase() == 'BODY' && select_template_data[0].body_variable_count != 0) {
                                                logger_all.info(msg_json.template)

                                                var variable_array;
                                                for (var w = 0; w < msg_json.template.components.length; w++) {
                                                    if (msg_json.template.components[w].type.toLowerCase() == 'body') {
                                                        variable_array = msg_json.template.components[w].parameters;
                                                    }
                                                }

                                                var variable_replaced_text = template_json[t].text;
                                                for (var v = 0; v < select_template_data[0].body_variable_count; v++) {
                                                    var rep_text = `{{${v + 1}}}`;
                                                    variable_replaced_text = variable_replaced_text.replace(rep_text, variable_array[v].text)
                                                }

                                                var buff_bot_reply = new Buffer(variable_replaced_text);
                                                var bot_msg = buff_bot_reply.toString('base64');

                                                logger_all.info(" [insert query request] : " + `INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}','${bot_msg}',NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`)
                                                insert_bot_reply = await db.query(`INSERT INTO messenger_response VALUES(NULL,${select_usr_id[0].user_id},'${from}','${to}','${pro_name}','-','text','${JSON.stringify(msg_json)}',"${bot_msg}",NULL,NULL,NULL,NULL,NULL,NULL,'N','N',CURRENT_TIMESTAMP,'0000-00-00 00:00:00')`);
                                                logger_all.info(" [insert query response] : " + JSON.stringify(insert_bot_reply))
                                            }
                                        }
                                    }

                                    break;
                                // case "":
                                // 	break;
                            }

                            msg_json['messaging_product'] = "whatsapp";
                            msg_json['recipient_type'] = "individual";
                            msg_json['to'] = from;
                            var chat_msg_json = {
                                method: 'post',
                                url: `${api_url}${select_usr_id[0].phone_number_id}/messages`,
                                headers: {
                                    'Authorization': 'Bearer ' + select_usr_id[0].bearer_token,
                                },
                                data: msg_json
                            };

                            logger_all.info(" [chatbot msg  request] : " + JSON.stringify(chat_msg_json))

                            await axios(chat_msg_json)
                                .then(async function (response) {

                                    logger_all.info(" [chatbot msg response] : " + JSON.stringify(response.data))

                                    if (insert_bot_reply) {
                                        logger_all.info(" [update query request] : " + `UPDATE messenger_response SET message_resp_id = '${response.data.messages[0].id}',message_status = 'Y' WHERE message_id = ${insert_bot_reply.insertId}`)
                                        const update_success = await db.query(`UPDATE messenger_response SET message_resp_id = '${response.data.messages[0].id}',message_status = 'Y' WHERE message_id = ${insert_bot_reply.insertId}`);
                                        logger_all.info(" [update query response] : " + JSON.stringify(update_success))
                                    }
                                    //await sleep(2000)

                                })
                                .catch(async function (error) {
                                    logger_all.info(" [chatbot msg failed response] : " + error)

                                    if (insert_bot_reply) {
                                        logger_all.info(" [update query request] : " + `UPDATE messenger_response SET message_status = 'F' WHERE message_id = ${insert_bot_reply.insertId}`)
                                        const update_failure = await db.query(`UPDATE messenger_response SET message_status = 'F' WHERE message_id = ${insert_bot_reply.insertId}`);
                                        logger_all.info(" [update query response] : " + JSON.stringify(update_failure))
                                    }

                                })

                            //await sleep(30)
                        }
                    }
                }

            }
        }
    }
    catch (e) {
        logger_all.info(" [receive message failed response] : " + e)
    }

    response.sendStatus(200);
});

// to api for create_csv 
app.post('/create_csv', validator.body(CreateCsvValidation),
    valid_user, async function (req, res) {

        try {
            var header_json = req.headers;
            let ip_address = header_json['x-forwarded-for'];

            // to get date and time
            var day = new Date();
            var today_date = day.getFullYear() + '' + (day.getMonth() + 1) + '' + day.getDate();
            var today_time = day.getHours() + "" + day.getMinutes() + "" + day.getSeconds();
            var current_date = today_date + '_' + today_time;
            // get all the req data
            let sender_number = req.body.mobile_number;

            logger.info(" [create csv query parameters] : " + sender_number)

            const insert_api_log = `INSERT INTO api_log VALUES(NULL,'${req.originalUrl}','${ip_address}','${req.body.request_id}','N','-','0000-00-00 00:00:00','Y',CURRENT_TIMESTAMP)`
            logger_all.info("[insert query request] : " + insert_api_log);
            const insert_api_log_result = await db.query(insert_api_log);
            logger_all.info("[insert query response] : " + JSON.stringify(insert_api_log_result))

            const check_req_id = `SELECT * FROM api_log WHERE request_id = '${req.body.request_id}' AND response_status != 'N' AND log_status='Y'`
            logger_all.info("[select query request] : " + check_req_id);
            const check_req_id_result = await db.query(check_req_id);
            logger_all.info("[select query response] : " + JSON.stringify(check_req_id_result));

            if (check_req_id_result.length != 0) {

                logger_all.info("[failed response] : Request already processed");
                logger.info("[API RESPONSE] " + JSON.stringify({ request_id: req.body.request_id, response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Request already processed' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                return res.json({ response_code: 0, response_status: 201, response_msg: 'Request already processed', request_id: req.body.request_id });

            }

            // to get the data in the array 
            var data = [
                ['Name', 'Given Name', 'Group Membership', 'Phone 1 - Type', 'Phone 1 - Value']
            ];
            // looping condition is true .to continue the process
            for (var i = 0; i < sender_number.length; i++) {
                data.push([`yjtec${day.getDate()}_${sender_number[i]}`, `yjtec${day.getDate()}_${sender_number[i]}`, '* myContacts', '', `${sender_number[i]}`])
            }

            // (C) CREATE CSV FILE to send the response in success message
            csv.stringify(data, async (err, output) => {
                fs.writeFileSync(`${media_storage}/uploads/whatsapp_docs/contacts_${current_date}.csv`, output);
                logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 1, response_status: 200, response_msg: 'Success ', file_location: `uploads/whatsapp_docs/contacts_${current_date}.csv`, request_id: req.body.request_id }))

                var log_update = `UPDATE api_log SET response_status = 'S',response_date = CURRENT_TIMESTAMP, response_comments = 'Success' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
                logger.silly("[update query request] : " + log_update);
                const log_update_result = await db.query(log_update);
                logger.silly("[update query response] : " + JSON.stringify(log_update_result))

                res.json({ response_code: 1, response_status: 200, response_msg: 'Success ', file_location: `uploads/whatsapp_docs/contacts_${current_date}.csv`, request_id: req.body.request_id });
            });

        }
        catch (e) {// any error occurres send error response to client
            logger_all.info("[create csv failed response] : " + e)
            logger.info("[API RESPONSE] " + JSON.stringify({ response_code: 0, response_status: 201, response_msg: 'Error Occurred', request_id: req.body.request_id }))

            var log_update = `UPDATE api_log SET response_status = 'F',response_date = CURRENT_TIMESTAMP, response_comments = 'Error occurred' WHERE request_id = '${req.body.request_id}' AND response_status = 'N'`
            logger.silly("[update query request] : " + log_update);
            const log_update_result = await db.query(log_update);
            logger.silly("[update query response] : " + JSON.stringify(log_update_result))

            res.json({ response_code: 0, response_status: 201, response_msg: 'Error Occurred', request_id: req.body.request_id });
        }
    });
// to listen the port in using the localhost
// app.listen(port, () => {
// 	logger_all.info(`App started listening at http://localhost:${port}`);
// });

// module.exports.logger = logger;

//  to listen the port in using the server

httpServer.listen(port, function (req, res) {
    logger_all.info("Server started at port " + port);
});
