var fs = require('fs');
var moment = require('moment');
var express = require('express');
var io = require('socket.io');
var cls = require('continuation-local-storage');
var namespace = cls.createNamespace('myAppNamespace');
var Sequelize = require('sequelize');
    Sequelize.cls = namespace;
var _ = require('lodash');
var http = require('request');
var SerialPort = require("serialport");
var temporal = require("temporal");
var phonesms = "0973980091";

var sequelize = new Sequelize('sim', 'root', 'luyen@123', {
    host: 'localhost',
    dialect: 'mysql',
    logging: false,
    pool: {
        max: 10,
        min: 0,
        idle: 5000
    }
});

var Phones = sequelize.define('Phones', {
    phone : Sequelize.STRING,
    loai : Sequelize.INTEGER,
    type : Sequelize.INTEGER,
    userid : Sequelize.INTEGER,
    canthanhtoan : Sequelize.INTEGER,
    dathanhtoan : Sequelize.INTEGER,
    gop : Sequelize.INTEGER,
    last_balance : Sequelize.INTEGER,
    time : Sequelize.INTEGER,
    orders : Sequelize.INTEGER,
    status : Sequelize.INTEGER
}, {
    timestamps: false
});

var Cards = sequelize.define('Cards', {
    phone_id : Sequelize.INTEGER,
    pin : Sequelize.STRING,
    seri : Sequelize.STRING,
    price : Sequelize.INTEGER,
    time : Sequelize.INTEGER,
    orders : Sequelize.INTEGER,
    status : Sequelize.INTEGER
}, {
    timestamps: false
});

var LogCards = sequelize.define('LogCards', {
    pin : Sequelize.STRING,
    seri : Sequelize.STRING,
    price : Sequelize.INTEGER,
    time : Sequelize.INTEGER,
    msg : Sequelize.STRING,
}, {
    timestamps: false
});
var DiscountPercentage = sequelize.define('DiscountPercentage',{
user_id:Sequelize.INTEGER,
viettel_percent:Sequelize.FLOAT,
mobi_percent:Sequelize.FLOAT,
vina_percent:Sequelize.FLOAT,
ftth_percent:Sequelize.FLOAT,
orders:Sequelize.INTEGER,
status:Sequelize.INTEGER,
},
{
      timestamps: false,
tableName:'DiscountPercentage'
});
var DiscountHistories = sequelize.define('DiscountHistories',{
        user_id : Sequelize.INTEGER,
     phone_id : Sequelize.INTEGER,
     money : Sequelize.INTEGER,
     unpaid_amount : Sequelize.INTEGER,
     real_discount : Sequelize.FLOAT,
     discount_percent : Sequelize.FLOAT,
     discount_money : Sequelize.INTEGER,
     real_money : Sequelize.INTEGER,
     trans_type : Sequelize.INTEGER,
     orders : Sequelize.INTEGER,
     status : Sequelize.INTEGER
}, {
    timestamps: false
});
var CommissionHistories = sequelize.define('CommissionHistories',{
     user_id : Sequelize.INTEGER,
     discount : Sequelize.FLOAT,
     money : Sequelize.INTEGER,
     discount_histories_id : Sequelize.INTEGER,
      time : Sequelize.INTEGER,
     orders : Sequelize.INTEGER,
     status : Sequelize.INTEGER
}, {
    timestamps: false
});
var Histories = sequelize.define('Histories',{
               user_id : Sequelize.INTEGER,
     cur_balance : Sequelize.INTEGER,
     money : Sequelize.INTEGER,
     up_balance : Sequelize.INTEGER,
     time : Sequelize.INTEGER,
     note : Sequelize.STRING,
     orders : Sequelize.INTEGER,
     status : Sequelize.INTEGER
}, {
    timestamps: false
});
var Users = sequelize.define('Users', {
    refer : Sequelize.INTEGER,
    name : Sequelize.STRING,
    phone : Sequelize.STRING,
    balance : Sequelize.INTEGER,
    salt : Sequelize.STRING,
    password : Sequelize.STRING,
    trans_pass : Sequelize.STRING,
    change_pass : Sequelize.INTEGER,
    scret_key : Sequelize.STRING,
    group_id : Sequelize.INTEGER,
    time : Sequelize.INTEGER,
    orders : Sequelize.INTEGER,
    status : Sequelize.INTEGER
}, {
    timestamps: false
});

var app = express();
var server = app.listen(29995, function() {
    console.log('Server is runing...ok');
});
var socketServer = io(server);

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

var usbs = [
    {port : "/dev/ttyUSB0", phone : '01652180688'},
    {port : "/dev/ttyUSB1", phone : '01652180688'},
    {port : "/dev/ttyUSB2", phone : '01652180688'},
    {port : "/dev/ttyUSB3", phone : '01652180688'},
    {port : "/dev/ttyUSB4", phone : '01652180688'},
    {port : "/dev/ttyUSB5", phone : '01652180688'},
    {port : "/dev/ttyUSB6", phone : '01652180688'},
    {port : "/dev/ttyUSB7", phone : '01652180688'},
    {port : "/dev/ttyUSB8", phone : '01652180688'},
    {port : "/dev/ttyUSB9", phone : '01652180688'},
    {port : "/dev/ttyUSB10", phone : '01652180688'},
    {port : "/dev/ttyUSB11", phone : '01652180688'},
    {port : "/dev/ttyUSB12", phone : '01652180688'},
    {port : "/dev/ttyUSB13", phone : '01652180688'},
    {port : "/dev/ttyUSB14", phone : '01652180688'},
    {port : "/dev/ttyUSB15", phone : '01652180688'},
    {port : "/dev/ttyUSB16", phone : '01652180688'},
    {port : "/dev/ttyUSB17", phone : '01652180688'},
    {port : "/dev/ttyUSB18", phone : '01652180688'},
    {port : "/dev/ttyUSB19", phone : '01652180688'},
    {port : "/dev/ttyUSB20", phone : '01652180688'},
    {port : "/dev/ttyUSB21", phone : '01652180688'},
    {port : "/dev/ttyUSB22", phone : '01652180688'},
    {port : "/dev/ttyUSB23", phone : '01652180688'},
    {port : "/dev/ttyUSB24", phone : '01652180688'},
    {port : "/dev/ttyUSB25", phone : '01652180688'},
    {port : "/dev/ttyUSB26", phone : '01652180688'},
    {port : "/dev/ttyUSB27", phone : '01652180688'},
    {port : "/dev/ttyUSB28", phone : '01652180688'},
    {port : "/dev/ttyUSB29", phone : '01652180688'},
    {port : "/dev/ttyUSB30", phone : '01652180688'},
    {port : "/dev/ttyUSB31", phone : '01652180688'},
    {port : "/dev/ttyUSB32", phone : '01652180688'},
    {port : "/dev/ttyUSB33", phone : '01652180688'},
    {port : "/dev/ttyUSB34", phone : '01652180688'},
    {port : "/dev/ttyUSB35", phone : '01652180688'},
    {port : "/dev/ttyUSB36", phone : '01652180688'},
    {port : "/dev/ttyUSB37", phone : '01652180688'},
    {port : "/dev/ttyUSB38", phone : '01652180688'},
    {port : "/dev/ttyUSB39", phone : '01652180688'},
    {port : "/dev/ttyUSB40", phone : '01652180688'},
    {port : "/dev/ttyUSB41", phone : '01652180688'},
    {port : "/dev/ttyUSB42", phone : '01652180688'},
    {port : "/dev/ttyUSB43", phone : '01652180688'},
    {port : "/dev/ttyUSB44", phone : '01652180688'},
    {port : "/dev/ttyUSB45", phone : '01652180688'},
    {port : "/dev/ttyUSB46", phone : '01652180688'},
    {port : "/dev/ttyUSB47", phone : '01652180688'},
    {port : "/dev/ttyUSB48", phone : '01652180688'},
    {port : "/dev/ttyUSB49", phone : '01652180688'},
    {port : "/dev/ttyUSB50", phone : '01652180688'},
    {port : "/dev/ttyUSB51", phone : '01652180688'},
    {port : "/dev/ttyUSB52", phone : '01652180688'},
    {port : "/dev/ttyUSB53", phone : '01652180688'},
    {port : "/dev/ttyUSB54", phone : '01652180688'},
    {port : "/dev/ttyUSB55", phone : '01652180688'},
    {port : "/dev/ttyUSB56", phone : '01652180688'},
    {port : "/dev/ttyUSB57", phone : '01652180688'},
    {port : "/dev/ttyUSB58", phone : '01652180688'},
    {port : "/dev/ttyUSB59", phone : '01652180688'},
    {port : "/dev/ttyUSB60", phone : '01652180688'},
    {port : "/dev/ttyUSB61", phone : '01652180688'},
    {port : "/dev/ttyUSB62", phone : '01652180688'},
    {port : "/dev/ttyUSB63", phone : '01652180688'},
    {port : "/dev/ttyUSB64", phone : '01652180688'},
    {port : "/dev/ttyUSB65", phone : '01652180688'},
    {port : "/dev/ttyUSB66", phone : '01652180688'},
    {port : "/dev/ttyUSB67", phone : '01652180688'},
    {port : "/dev/ttyUSB68", phone : '01652180688'},
    {port : "/dev/ttyUSB69", phone : '01652180688'},
    {port : "/dev/ttyUSB70", phone : '01652180688'},
    {port : "/dev/ttyUSB71", phone : '01652180688'},
    {port : "/dev/ttyUSB72", phone : '01652180688'},
    {port : "/dev/ttyUSB73", phone : '01652180688'},
    {port : "/dev/ttyUSB74", phone : '01652180688'},
    {port : "/dev/ttyUSB75", phone : '01652180688'},
    {port : "/dev/ttyUSB76", phone : '01652180688'},
    {port : "/dev/ttyUSB77", phone : '01652180688'},
    {port : "/dev/ttyUSB78", phone : '01652180688'},
    {port : "/dev/ttyUSB79", phone : '01652180688'},
    {port : "/dev/ttyUSB80", phone : '01652180688'},
    {port : "/dev/ttyUSB81", phone : '01652180688'},
    {port : "/dev/ttyUSB82", phone : '01652180688'},
    {port : "/dev/ttyUSB83", phone : '01652180688'},
    {port : "/dev/ttyUSB84", phone : '01652180688'},
    {port : "/dev/ttyUSB85", phone : '01652180688'},
    {port : "/dev/ttyUSB86", phone : '01652180688'},
    {port : "/dev/ttyUSB87", phone : '01652180688'},
    {port : "/dev/ttyUSB88", phone : '01652180688'},
    {port : "/dev/ttyUSB89", phone : '01652180688'},
    {port : "/dev/ttyUSB90", phone : '01652180688'},
    {port : "/dev/ttyUSB91", phone : '01652180688'},
    {port : "/dev/ttyUSB92", phone : '01652180688'},
    {port : "/dev/ttyUSB93", phone : '01652180688'},
    {port : "/dev/ttyUSB94", phone : '01652180688'},
    {port : "/dev/ttyUSB95", phone : '01652180688'},
    {port : "/dev/ttyUSB96", phone : '01652180688'},
    {port : "/dev/ttyUSB97", phone : '01652180688'},
    {port : "/dev/ttyUSB98", phone : '01652180688'},
    {port : "/dev/ttyUSB99", phone : '01652180688'},
    {port : "/dev/ttyUSB100", phone : '01652180688'},
    {port : "/dev/ttyUSB101", phone : '01652180688'},
    {port : "/dev/ttyUSB102", phone : '01652180688'},
    {port : "/dev/ttyUSB103", phone : '01652180688'},
    {port : "/dev/ttyUSB104", phone : '01652180688'},
    {port : "/dev/ttyUSB105", phone : '01652180688'},
    {port : "/dev/ttyUSB106", phone : '01652180688'},
    {port : "/dev/ttyUSB107", phone : '01652180688'},
    {port : "/dev/ttyUSB108", phone : '01652180688'},
    {port : "/dev/ttyUSB109", phone : '01652180688'},
    {port : "/dev/ttyUSB110", phone : '01652180688'},
    {port : "/dev/ttyUSB111", phone : '01652180688'},
    {port : "/dev/ttyUSB112", phone : '01652180688'},
    {port : "/dev/ttyUSB113", phone : '01652180688'},
    {port : "/dev/ttyUSB114", phone : '01652180688'},
    {port : "/dev/ttyUSB115", phone : '01652180688'},
    {port : "/dev/ttyUSB116", phone : '01652180688'},
    {port : "/dev/ttyUSB117", phone : '01652180688'},
    {port : "/dev/ttyUSB118", phone : '01652180688'},
    {port : "/dev/ttyUSB119", phone : '01652180688'},
    {port : "/dev/ttyUSB120", phone : '01652180688'},
    {port : "/dev/ttyUSB121", phone : '01652180688'},
    {port : "/dev/ttyUSB122", phone : '01652180688'},
    {port : "/dev/ttyUSB123", phone : '01652180688'},
    {port : "/dev/ttyUSB124", phone : '01652180688'},
    {port : "/dev/ttyUSB125", phone : '01652180688'},
    {port : "/dev/ttyUSB126", phone : '01652180688'},
    {port : "/dev/ttyUSB127", phone : '01652180688'},
    {port : "/dev/ttyUSB128", phone : '01652180688'},
    {port : "/dev/ttyUSB129", phone : '01652180688'},
    {port : "/dev/ttyUSB130", phone : '01652180688'},
    {port : "/dev/ttyUSB131", phone : '01652180688'},
    {port : "/dev/ttyUSB132", phone : '01652180688'},
    {port : "/dev/ttyUSB133", phone : '01652180688'},
    {port : "/dev/ttyUSB134", phone : '01652180688'},
    {port : "/dev/ttyUSB135", phone : '01652180688'},
    {port : "/dev/ttyUSB136", phone : '01652180688'},
    {port : "/dev/ttyUSB137", phone : '01652180688'},
    {port : "/dev/ttyUSB138", phone : '01652180688'},
    {port : "/dev/ttyUSB139", phone : '01652180688'},   
    {port : "/dev/ttyUSB140", phone : '01652180688'},
    {port : "/dev/ttyUSB141", phone : '01652180688'},
    {port : "/dev/ttyUSB142", phone : '01652180688'},
    {port : "/dev/ttyUSB143", phone : '01652180688'},
    {port : "/dev/ttyUSB144", phone : '01652180688'},
    {port : "/dev/ttyUSB145", phone : '01652180688'},
    {port : "/dev/ttyUSB146", phone : '01652180688'},
    {port : "/dev/ttyUSB147", phone : '01652180688'},
    {port : "/dev/ttyUSB148", phone : '01652180688'},
    {port : "/dev/ttyUSB149", phone : '01652180688'},
    {port : "/dev/ttyUSB150", phone : '01652180688'},
    {port : "/dev/ttyUSB151", phone : '01652180688'},
    {port : "/dev/ttyUSB152", phone : '01652180688'},
    {port : "/dev/ttyUSB153", phone : '01652180688'},
    {port : "/dev/ttyUSB154", phone : '01652180688'},
    {port : "/dev/ttyUSB155", phone : '01652180688'},
    {port : "/dev/ttyUSB156", phone : '01652180688'},
    {port : "/dev/ttyUSB157", phone : '01652180688'},
    {port : "/dev/ttyUSB158", phone : '01652180688'},
    {port : "/dev/ttyUSB159", phone : '01652180688'},
    
];
var ports = [];
var dataresArr = [];

// setup port0
var port0 = new SerialPort(usbs[0].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port0.on("data", function(res) {
    dataresArr[0] = dataresArr[0] + res;
});
ports.push({port : port0, phone : usbs[0].phone, status : 0, fail : 0, type : 1});

// setup port1
var port1 = new SerialPort(usbs[1].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port1.on("data", function(res) {
    dataresArr[1] = dataresArr[1] + res;
});
ports.push({port : port1, phone : usbs[1].phone, status : 0, fail : 0, type : 1});

// setup port2
var port2 = new SerialPort(usbs[2].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port2.on("data", function(res) {
    dataresArr[2] = dataresArr[2] + res;
});
ports.push({port : port2, phone : usbs[2].phone, status : 0, fail : 0, type : 1});

// setup port3
var port3 = new SerialPort(usbs[3].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port3.on("data", function(res) {
    dataresArr[3] = dataresArr[3] + res;
});
ports.push({port : port3, phone : usbs[3].phone, status : 0, fail : 0, type : 1});


// setup port4
var port4 = new SerialPort(usbs[4].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port4.on("data", function(res) {
    dataresArr[4] = dataresArr[4] + res;
});
ports.push({port : port4, phone : usbs[4].phone, status : 0, fail : 0, type : 1});

// setup port5
var port5 = new SerialPort(usbs[5].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port5.on("data", function(res) {
    dataresArr[5] = dataresArr[5] + res;
});
ports.push({port : port5, phone : usbs[5].phone, status : 0, fail : 0, type : 1});


// setup port6
var port6 = new SerialPort(usbs[6].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port6.on("data", function(res) {
    dataresArr[6] = dataresArr[6] + res;
});
ports.push({port : port6, phone : usbs[6].phone, status : 0, fail : 0, type : 1});


// setup port7
var port7 = new SerialPort(usbs[7].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port7.on("data", function(res) {
    dataresArr[7] = dataresArr[7] + res;
});
ports.push({port : port7, phone : usbs[7].phone, status : 0, fail : 0, type : 1});

// setup port8
var port8 = new SerialPort(usbs[8].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port8.on("data", function(res) {
    dataresArr[8] = dataresArr[8] + res;
});
ports.push({port : port8, phone : usbs[8].phone, status : 0, fail : 0, type : 1});

// setup port9
var port9 = new SerialPort(usbs[9].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port9.on("data", function(res) {
    dataresArr[9] = dataresArr[9] + res;
});
ports.push({port : port9, phone : usbs[9].phone, status : 0, fail : 0, type : 1});

// setup port10
var port10 = new SerialPort(usbs[10].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port10.on("data", function(res) {
    dataresArr[10] = dataresArr[10] + res;
});
ports.push({port : port10, phone : usbs[10].phone, status : 0, fail : 0, type : 1});

// setup port11
var port11 = new SerialPort(usbs[11].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port11.on("data", function(res) {
    dataresArr[11] = dataresArr[11] + res;
});
ports.push({port : port11, phone : usbs[11].phone, status : 0, fail : 0, type : 1});

// setup port12
var port12 = new SerialPort(usbs[12].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port12.on("data", function(res) {
    dataresArr[12] = dataresArr[12] + res;
});
ports.push({port : port12, phone : usbs[12].phone, status : 0, fail : 0, type : 1});

// setup port13
var port13 = new SerialPort(usbs[13].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port13.on("data", function(res) {
    dataresArr[13] = dataresArr[13] + res;
});
ports.push({port : port13, phone : usbs[13].phone, status : 0, fail : 0, type : 1});

// setup port14
var port14 = new SerialPort(usbs[14].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port14.on("data", function(res) {
    dataresArr[14] = dataresArr[14] + res;
});
ports.push({port : port14, phone : usbs[14].phone, status : 0, fail : 0, type : 1});

// setup port15
var port15 = new SerialPort(usbs[15].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port15.on("data", function(res) {
    dataresArr[15] = dataresArr[15] + res;
});
ports.push({port : port15, phone : usbs[15].phone, status : 0, fail : 0, type : 1});

// setup port16
var port16 = new SerialPort(usbs[16].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port16.on("data", function(res) {
    dataresArr[16] = dataresArr[16] + res;
});
ports.push({port : port16, phone : usbs[16].phone, status : 0, fail : 0, type : 1});

// setup port17
var port17 = new SerialPort(usbs[17].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port17.on("data", function(res) {
    dataresArr[17] = dataresArr[17] + res;
});
ports.push({port : port17, phone : usbs[17].phone, status : 0, fail : 0, type : 1});

// setup port18
var port18 = new SerialPort(usbs[18].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port18.on("data", function(res) {
    dataresArr[18] = dataresArr[18] + res;
});
ports.push({port : port18, phone : usbs[18].phone, status : 0, fail : 0, type : 1});

// setup port19
var port19 = new SerialPort(usbs[19].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port19.on("data", function(res) {
    dataresArr[19] = dataresArr[19] + res;
});
ports.push({port : port19, phone : usbs[19].phone, status : 0, fail : 0, type : 1});

// setup port20
var port20 = new SerialPort(usbs[20].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port20.on("data", function(res) {
    dataresArr[20] = dataresArr[20] + res;
});
ports.push({port : port20, phone : usbs[20].phone, status : 0, fail : 0, type : 1});

// setup port21
var port21 = new SerialPort(usbs[21].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port21.on("data", function(res) {
    dataresArr[21] = dataresArr[21] + res;
});
ports.push({port : port21, phone : usbs[21].phone, status : 0, fail : 0, type : 1});

// setup port22
var port22 = new SerialPort(usbs[22].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port22.on("data", function(res) {
    dataresArr[22] = dataresArr[22] + res;
});
ports.push({port : port22, phone : usbs[22].phone, status : 0, fail : 0, type : 1});

// setup port23
var port23 = new SerialPort(usbs[23].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port23.on("data", function(res) {
    dataresArr[23] = dataresArr[23] + res;
});
ports.push({port : port23, phone : usbs[23].phone, status : 0, fail : 0, type : 1});

// setup port24
var port24 = new SerialPort(usbs[24].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port24.on("data", function(res) {
    dataresArr[24] = dataresArr[24] + res;
});
ports.push({port : port24, phone : usbs[24].phone, status : 0, fail : 0, type : 1});

// setup port25
var port25 = new SerialPort(usbs[25].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port25.on("data", function(res) {
    dataresArr[25] = dataresArr[25] + res;
});
ports.push({port : port25, phone : usbs[25].phone, status : 0, fail : 0, type : 1});

// setup port26
var port26 = new SerialPort(usbs[26].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port26.on("data", function(res) {
    dataresArr[26] = dataresArr[26] + res;
});
ports.push({port : port26, phone : usbs[26].phone, status : 0, fail : 0, type : 1});

// setup port27
var port27 = new SerialPort(usbs[27].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port27.on("data", function(res) {
    dataresArr[27] = dataresArr[27] + res;
});
ports.push({port : port27, phone : usbs[27].phone, status : 0, fail : 0, type : 1});

// setup port28
var port28 = new SerialPort(usbs[28].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port28.on("data", function(res) {
    dataresArr[28] = dataresArr[28] + res;
});
ports.push({port : port28, phone : usbs[28].phone, status : 0, fail : 0, type : 1});

// setup port29
var port29 = new SerialPort(usbs[29].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port29.on("data", function(res) {
    dataresArr[29] = dataresArr[29] + res;
});
ports.push({port : port29, phone : usbs[29].phone, status : 0, fail : 0, type : 1});

// setup port30
var port30 = new SerialPort(usbs[30].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port30.on("data", function(res) {
    dataresArr[30] = dataresArr[30] + res;
});
ports.push({port : port30, phone : usbs[30].phone, status : 0, fail : 0, type : 1});

// setup port31
var port31 = new SerialPort(usbs[31].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port31.on("data", function(res) {
    dataresArr[31] = dataresArr[31] + res;
});
ports.push({port : port31, phone : usbs[31].phone, status : 0, fail : 0, type : 1});

// setup port32
var port32 = new SerialPort(usbs[32].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port32.on("data", function(res) {
    dataresArr[32] = dataresArr[32] + res;
});
ports.push({port : port32, phone : usbs[32].phone, status : 0, fail : 0, type : 1});

// setup port33
var port33 = new SerialPort(usbs[33].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port33.on("data", function(res) {
    dataresArr[33] = dataresArr[33] + res;
});
ports.push({port : port33, phone : usbs[33].phone, status : 0, fail : 0, type : 1});

// setup port34
var port34 = new SerialPort(usbs[34].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port34.on("data", function(res) {
    dataresArr[34] = dataresArr[34] + res;
});
ports.push({port : port34, phone : usbs[34].phone, status : 0, fail : 0, type : 1});

// setup port35
var port35 = new SerialPort(usbs[35].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port35.on("data", function(res) {
    dataresArr[35] = dataresArr[35] + res;
});
ports.push({port : port35, phone : usbs[35].phone, status : 0, fail : 0, type : 1});

// setup port36
var port36 = new SerialPort(usbs[36].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port36.on("data", function(res) {
    dataresArr[36] = dataresArr[36] + res;
});
ports.push({port : port36, phone : usbs[36].phone, status : 0, fail : 0, type : 1});

// setup port37
var port37 = new SerialPort(usbs[37].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port37.on("data", function(res) {
    dataresArr[37] = dataresArr[37] + res;
});
ports.push({port : port37, phone : usbs[37].phone, status : 0, fail : 0, type : 1});

// setup port38
var port38 = new SerialPort(usbs[38].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port38.on("data", function(res) {
    dataresArr[38] = dataresArr[38] + res;
});
ports.push({port : port38, phone : usbs[38].phone, status : 0, fail : 0, type : 1});

// setup port39
var port39 = new SerialPort(usbs[39].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port39.on("data", function(res) {
    dataresArr[39] = dataresArr[39] + res;
});
ports.push({port : port39, phone : usbs[39].phone, status : 0, fail : 0, type : 1});

// setup port40
var port40 = new SerialPort(usbs[40].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port40.on("data", function(res) {
    dataresArr[40] = dataresArr[40] + res;
});
ports.push({port : port40, phone : usbs[40].phone, status : 0, fail : 0, type : 1});

// setup port41
var port41 = new SerialPort(usbs[41].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port41.on("data", function(res) {
    dataresArr[41] = dataresArr[41] + res;
});
ports.push({port : port41, phone : usbs[41].phone, status : 0, fail : 0, type : 1});

// setup port42
var port42 = new SerialPort(usbs[42].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port42.on("data", function(res) {
    dataresArr[42] = dataresArr[42] + res;
});
ports.push({port : port42, phone : usbs[42].phone, status : 0, fail : 0, type : 1});

// setup port43
var port43 = new SerialPort(usbs[43].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port43.on("data", function(res) {
    dataresArr[43] = dataresArr[43] + res;
});
ports.push({port : port43, phone : usbs[43].phone, status : 0, fail : 0, type : 1});

// setup port44
var port44 = new SerialPort(usbs[44].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port44.on("data", function(res) {
    dataresArr[44] = dataresArr[44] + res;
});
ports.push({port : port44, phone : usbs[44].phone, status : 0, fail : 0, type : 1});

// setup port45
var port45 = new SerialPort(usbs[45].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port45.on("data", function(res) {
    dataresArr[45] = dataresArr[45] + res;
});
ports.push({port : port45, phone : usbs[45].phone, status : 0, fail : 0, type : 1});

// setup port46
var port46 = new SerialPort(usbs[46].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port46.on("data", function(res) {
    dataresArr[46] = dataresArr[46] + res;
});
ports.push({port : port46, phone : usbs[46].phone, status : 0, fail : 0, type : 1});

// setup port47
var port47 = new SerialPort(usbs[47].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port47.on("data", function(res) {
    dataresArr[47] = dataresArr[47] + res;
});
ports.push({port : port47, phone : usbs[47].phone, status : 0, fail : 0, type : 1});

// setup port48
var port48 = new SerialPort(usbs[48].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port48.on("data", function(res) {
    dataresArr[48] = dataresArr[48] + res;
});
ports.push({port : port48, phone : usbs[48].phone, status : 0, fail : 0, type : 1});

// setup port49
var port49 = new SerialPort(usbs[49].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port49.on("data", function(res) {
    dataresArr[49] = dataresArr[49] + res;
});
ports.push({port : port49, phone : usbs[49].phone, status : 0, fail : 0, type : 1});

// setup port50
var port50 = new SerialPort(usbs[50].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port50.on("data", function(res) {
    dataresArr[50] = dataresArr[50] + res;
});
ports.push({port : port50, phone : usbs[50].phone, status : 0, fail : 0, type : 1});

// setup port51
var port51 = new SerialPort(usbs[51].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port51.on("data", function(res) {
    dataresArr[51] = dataresArr[51] + res;
});
ports.push({port : port51, phone : usbs[51].phone, status : 0, fail : 0, type : 1});

// setup port52
var port52 = new SerialPort(usbs[52].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port52.on("data", function(res) {
    dataresArr[52] = dataresArr[52] + res;
});
ports.push({port : port52, phone : usbs[52].phone, status : 0, fail : 0, type : 1});

// setup port53
var port53 = new SerialPort(usbs[53].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port53.on("data", function(res) {
    dataresArr[53] = dataresArr[53] + res;
});
ports.push({port : port53, phone : usbs[53].phone, status : 0, fail : 0, type : 1});

// setup port54
var port54 = new SerialPort(usbs[54].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port54.on("data", function(res) {
    dataresArr[54] = dataresArr[54] + res;
});
ports.push({port : port54, phone : usbs[54].phone, status : 0, fail : 0, type : 1});

// setup port55
var port55 = new SerialPort(usbs[55].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port55.on("data", function(res) {
    dataresArr[55] = dataresArr[55] + res;
});
ports.push({port : port55, phone : usbs[55].phone, status : 0, fail : 0, type : 1});

// setup port56
var port56 = new SerialPort(usbs[56].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port56.on("data", function(res) {
    dataresArr[56] = dataresArr[56] + res;
});
ports.push({port : port56, phone : usbs[56].phone, status : 0, fail : 0, type : 1});

// setup port57
var port57 = new SerialPort(usbs[57].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port57.on("data", function(res) {
    dataresArr[57] = dataresArr[57] + res;
});
ports.push({port : port57, phone : usbs[57].phone, status : 0, fail : 0, type : 1});

// setup port58
var port58 = new SerialPort(usbs[58].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port58.on("data", function(res) {
    dataresArr[58] = dataresArr[58] + res;
});
ports.push({port : port58, phone : usbs[58].phone, status : 0, fail : 0, type : 1});

// setup port59
var port59 = new SerialPort(usbs[59].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port59.on("data", function(res) {
    dataresArr[59] = dataresArr[59] + res;
});
ports.push({port : port59, phone : usbs[59].phone, status : 0, fail : 0, type : 1});

// setup port60
var port60 = new SerialPort(usbs[60].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port60.on("data", function(res) {
    dataresArr[60] = dataresArr[60] + res;
});
ports.push({port : port60, phone : usbs[60].phone, status : 0, fail : 0, type : 1});

// setup port61
var port61 = new SerialPort(usbs[61].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port61.on("data", function(res) {
    dataresArr[61] = dataresArr[61] + res;
});
ports.push({port : port61, phone : usbs[61].phone, status : 0, fail : 0, type : 1});

// setup port62
var port62 = new SerialPort(usbs[62].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port62.on("data", function(res) {
    dataresArr[62] = dataresArr[62] + res;
});
ports.push({port : port62, phone : usbs[62].phone, status : 0, fail : 0, type : 1});

// setup port63
var port63 = new SerialPort(usbs[63].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port63.on("data", function(res) {
    dataresArr[63] = dataresArr[63] + res;
});
ports.push({port : port63, phone : usbs[63].phone, status : 0, fail : 0, type : 1});

// setup port64
var port64 = new SerialPort(usbs[64].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port64.on("data", function(res) {
    dataresArr[64] = dataresArr[64] + res;
});
ports.push({port : port64, phone : usbs[64].phone, status : 0, fail : 0, type : 1});

// setup port65
var port65 = new SerialPort(usbs[65].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port65.on("data", function(res) {
    dataresArr[65] = dataresArr[65] + res;
});
ports.push({port : port65, phone : usbs[65].phone, status : 0, fail : 0, type : 1});

// setup port66
var port66 = new SerialPort(usbs[66].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port66.on("data", function(res) {
    dataresArr[66] = dataresArr[66] + res;
});
ports.push({port : port66, phone : usbs[66].phone, status : 0, fail : 0, type : 1});

// setup port67
var port67 = new SerialPort(usbs[67].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port67.on("data", function(res) {
    dataresArr[67] = dataresArr[67] + res;
});
ports.push({port : port67, phone : usbs[67].phone, status : 0, fail : 0, type : 1});

// setup port68
var port68 = new SerialPort(usbs[68].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port68.on("data", function(res) {
    dataresArr[68] = dataresArr[68] + res;
});
ports.push({port : port68, phone : usbs[68].phone, status : 0, fail : 0, type : 1});

// setup port69
var port69 = new SerialPort(usbs[69].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port69.on("data", function(res) {
    dataresArr[69] = dataresArr[69] + res;
});
ports.push({port : port69, phone : usbs[69].phone, status : 0, fail : 0, type : 1});

// setup port70
var port70 = new SerialPort(usbs[70].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port70.on("data", function(res) {
    dataresArr[70] = dataresArr[70] + res;
});
ports.push({port : port70, phone : usbs[70].phone, status : 0, fail : 0, type : 1});

// setup port71
var port71 = new SerialPort(usbs[71].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port71.on("data", function(res) {
    dataresArr[71] = dataresArr[71] + res;
});
ports.push({port : port71, phone : usbs[71].phone, status : 0, fail : 0, type : 1});

// setup port72
var port72 = new SerialPort(usbs[72].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port72.on("data", function(res) {
    dataresArr[72] = dataresArr[72] + res;
});
ports.push({port : port72, phone : usbs[72].phone, status : 0, fail : 0, type : 1});

// setup port73
var port73 = new SerialPort(usbs[73].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port73.on("data", function(res) {
    dataresArr[73] = dataresArr[73] + res;
});
ports.push({port : port73, phone : usbs[73].phone, status : 0, fail : 0, type : 1});

// setup port74
var port74 = new SerialPort(usbs[74].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port74.on("data", function(res) {
    dataresArr[74] = dataresArr[74] + res;
});
ports.push({port : port74, phone : usbs[74].phone, status : 0, fail : 0, type : 1});

// setup port75
var port75 = new SerialPort(usbs[75].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port75.on("data", function(res) {
    dataresArr[75] = dataresArr[75] + res;
});
ports.push({port : port75, phone : usbs[75].phone, status : 0, fail : 0, type : 1});

// setup port76
var port76 = new SerialPort(usbs[76].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port76.on("data", function(res) {
    dataresArr[76] = dataresArr[76] + res;
});
ports.push({port : port76, phone : usbs[76].phone, status : 0, fail : 0, type : 1});

// setup port77
var port77 = new SerialPort(usbs[77].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port77.on("data", function(res) {
    dataresArr[77] = dataresArr[77] + res;
});
ports.push({port : port77, phone : usbs[77].phone, status : 0, fail : 0, type : 1});

// setup port78
var port78 = new SerialPort(usbs[78].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port78.on("data", function(res) {
    dataresArr[78] = dataresArr[78] + res;
});
ports.push({port : port78, phone : usbs[78].phone, status : 0, fail : 0, type : 1});

// setup port79
var port79 = new SerialPort(usbs[79].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port79.on("data", function(res) {
    dataresArr[79] = dataresArr[79] + res;
});
ports.push({port : port79, phone : usbs[79].phone, status : 0, fail : 0, type : 1});

// setup port80
var port80 = new SerialPort(usbs[80].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port80.on("data", function(res) {
    dataresArr[80] = dataresArr[80] + res;
});
ports.push({port : port80, phone : usbs[80].phone, status : 0, fail : 0, type : 1});

// setup port81
var port81 = new SerialPort(usbs[81].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port81.on("data", function(res) {
    dataresArr[81] = dataresArr[81] + res;
});
ports.push({port : port81, phone : usbs[81].phone, status : 0, fail : 0, type : 1});

// setup port82
var port82 = new SerialPort(usbs[82].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port82.on("data", function(res) {
    dataresArr[82] = dataresArr[82] + res;
});
ports.push({port : port82, phone : usbs[82].phone, status : 0, fail : 0, type : 1});

// setup port83
var port83 = new SerialPort(usbs[83].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port83.on("data", function(res) {
    dataresArr[83] = dataresArr[83] + res;
});
ports.push({port : port83, phone : usbs[83].phone, status : 0, fail : 0, type : 1});

// setup port84
var port84 = new SerialPort(usbs[84].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port84.on("data", function(res) {
    dataresArr[84] = dataresArr[84] + res;
});
ports.push({port : port84, phone : usbs[84].phone, status : 0, fail : 0, type : 1});

// setup port85
var port85 = new SerialPort(usbs[85].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port85.on("data", function(res) {
    dataresArr[85] = dataresArr[85] + res;
});
ports.push({port : port85, phone : usbs[85].phone, status : 0, fail : 0, type : 1});

// setup port86
var port86 = new SerialPort(usbs[86].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port86.on("data", function(res) {
    dataresArr[86] = dataresArr[86] + res;
});
ports.push({port : port86, phone : usbs[86].phone, status : 0, fail : 0, type : 1});

// setup port87
var port87 = new SerialPort(usbs[87].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port87.on("data", function(res) {
    dataresArr[87] = dataresArr[87] + res;
});
ports.push({port : port87, phone : usbs[87].phone, status : 0, fail : 0, type : 1});

// setup port88
var port88 = new SerialPort(usbs[88].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port88.on("data", function(res) {
    dataresArr[88] = dataresArr[88] + res;
});
ports.push({port : port88, phone : usbs[88].phone, status : 0, fail : 0, type : 1});

// setup port89
var port89 = new SerialPort(usbs[89].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port89.on("data", function(res) {
    dataresArr[89] = dataresArr[89] + res;
});
ports.push({port : port89, phone : usbs[89].phone, status : 0, fail : 0, type : 1});

// setup port90
var port90 = new SerialPort(usbs[90].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port90.on("data", function(res) {
    dataresArr[90] = dataresArr[90] + res;
});
ports.push({port : port90, phone : usbs[90].phone, status : 0, fail : 0, type : 1});

// setup port91
var port91 = new SerialPort(usbs[91].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port91.on("data", function(res) {
    dataresArr[91] = dataresArr[91] + res;
});
ports.push({port : port91, phone : usbs[91].phone, status : 0, fail : 0, type : 1});

// setup port92
var port92 = new SerialPort(usbs[92].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port92.on("data", function(res) {
    dataresArr[92] = dataresArr[92] + res;
});
ports.push({port : port92, phone : usbs[92].phone, status : 0, fail : 0, type : 1});

// setup port93
var port93 = new SerialPort(usbs[93].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port93.on("data", function(res) {
    dataresArr[93] = dataresArr[93] + res;
});
ports.push({port : port93, phone : usbs[93].phone, status : 0, fail : 0, type : 1});

// setup port94
var port94 = new SerialPort(usbs[94].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port94.on("data", function(res) {
    dataresArr[94] = dataresArr[94] + res;
});
ports.push({port : port94, phone : usbs[94].phone, status : 0, fail : 0, type : 1});

// setup port95
var port95 = new SerialPort(usbs[95].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port95.on("data", function(res) {
    dataresArr[95] = dataresArr[95] + res;
});
ports.push({port : port95, phone : usbs[95].phone, status : 0, fail : 0, type : 1});

// setup port96
var port96 = new SerialPort(usbs[96].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port96.on("data", function(res) {
    dataresArr[96] = dataresArr[96] + res;
});
ports.push({port : port96, phone : usbs[96].phone, status : 0, fail : 0, type : 1});

// setup port97
var port97 = new SerialPort(usbs[97].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port97.on("data", function(res) {
    dataresArr[97] = dataresArr[97] + res;
});
ports.push({port : port97, phone : usbs[97].phone, status : 0, fail : 0, type : 1});

// setup port98
var port98 = new SerialPort(usbs[98].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port98.on("data", function(res) {
    dataresArr[98] = dataresArr[98] + res;
});
ports.push({port : port98, phone : usbs[98].phone, status : 0, fail : 0, type : 1});

// setup port99
var port99 = new SerialPort(usbs[99].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port99.on("data", function(res) {
    dataresArr[99] = dataresArr[99] + res;
});
ports.push({port : port99, phone : usbs[99].phone, status : 0, fail : 0, type : 1});

// setup port100
var port100 = new SerialPort(usbs[100].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port100.on("data", function(res) {
    dataresArr[100] = dataresArr[100] + res;
});
ports.push({port : port100, phone : usbs[100].phone, status : 0, fail : 0, type : 1});

// setup port101
var port101 = new SerialPort(usbs[101].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port101.on("data", function(res) {
    dataresArr[101] = dataresArr[101] + res;
});
ports.push({port : port101, phone : usbs[101].phone, status : 0, fail : 0, type : 1});

// setup port102
var port102 = new SerialPort(usbs[102].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port102.on("data", function(res) {
    dataresArr[102] = dataresArr[102] + res;
});
ports.push({port : port102, phone : usbs[102].phone, status : 0, fail : 0, type : 1});

// setup port103
var port103 = new SerialPort(usbs[103].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port103.on("data", function(res) {
    dataresArr[103] = dataresArr[103] + res;
});
ports.push({port : port103, phone : usbs[103].phone, status : 0, fail : 0, type : 1});

// setup port104
var port104 = new SerialPort(usbs[104].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port104.on("data", function(res) {
    dataresArr[104] = dataresArr[104] + res;
});
ports.push({port : port104, phone : usbs[104].phone, status : 0, fail : 0, type : 1});

// setup port105
var port105 = new SerialPort(usbs[105].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port105.on("data", function(res) {
    dataresArr[105] = dataresArr[105] + res;
});
ports.push({port : port105, phone : usbs[105].phone, status : 0, fail : 0, type : 1});

// setup port106
var port106 = new SerialPort(usbs[106].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port106.on("data", function(res) {
    dataresArr[106] = dataresArr[106] + res;
});
ports.push({port : port106, phone : usbs[106].phone, status : 0, fail : 0, type : 1});

// setup port107
var port107 = new SerialPort(usbs[107].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port107.on("data", function(res) {
    dataresArr[107] = dataresArr[107] + res;
});
ports.push({port : port107, phone : usbs[107].phone, status : 0, fail : 0, type : 1});

// setup port108
var port108 = new SerialPort(usbs[108].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port108.on("data", function(res) {
    dataresArr[108] = dataresArr[108] + res;
});
ports.push({port : port108, phone : usbs[108].phone, status : 0, fail : 0, type : 1});

// setup port109
var port109 = new SerialPort(usbs[109].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port109.on("data", function(res) {
    dataresArr[109] = dataresArr[109] + res;
});
ports.push({port : port109, phone : usbs[109].phone, status : 0, fail : 0, type : 1});

// setup port110
var port110 = new SerialPort(usbs[110].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port110.on("data", function(res) {
    dataresArr[110] = dataresArr[110] + res;
});
ports.push({port : port110, phone : usbs[110].phone, status : 0, fail : 0, type : 1});

// setup port111
var port111 = new SerialPort(usbs[111].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port111.on("data", function(res) {
    dataresArr[111] = dataresArr[111] + res;
});
ports.push({port : port111, phone : usbs[111].phone, status : 0, fail : 0, type : 1});

// setup port112
var port112 = new SerialPort(usbs[112].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port112.on("data", function(res) {
    dataresArr[112] = dataresArr[112] + res;
});
ports.push({port : port112, phone : usbs[112].phone, status : 0, fail : 0, type : 1});

// setup port113
var port113 = new SerialPort(usbs[113].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port113.on("data", function(res) {
    dataresArr[113] = dataresArr[113] + res;
});
ports.push({port : port113, phone : usbs[113].phone, status : 0, fail : 0, type : 1});

// setup port114
var port114 = new SerialPort(usbs[114].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port114.on("data", function(res) {
    dataresArr[114] = dataresArr[114] + res;
});
ports.push({port : port114, phone : usbs[114].phone, status : 0, fail : 0, type : 1});

// setup port115
var port115 = new SerialPort(usbs[115].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port115.on("data", function(res) {
    dataresArr[115] = dataresArr[115] + res;
});
ports.push({port : port115, phone : usbs[115].phone, status : 0, fail : 0, type : 1});

// setup port116
var port116 = new SerialPort(usbs[116].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port116.on("data", function(res) {
    dataresArr[116] = dataresArr[116] + res;
});
ports.push({port : port116, phone : usbs[116].phone, status : 0, fail : 0, type : 1});

// setup port117
var port117 = new SerialPort(usbs[117].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port117.on("data", function(res) {
    dataresArr[117] = dataresArr[117] + res;
});
ports.push({port : port117, phone : usbs[117].phone, status : 0, fail : 0, type : 1});

// setup port118
var port118 = new SerialPort(usbs[118].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port118.on("data", function(res) {
    dataresArr[118] = dataresArr[118] + res;
});
ports.push({port : port118, phone : usbs[118].phone, status : 0, fail : 0, type : 1});

// setup port119
var port119 = new SerialPort(usbs[119].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port119.on("data", function(res) {
    dataresArr[119] = dataresArr[119] + res;
});
ports.push({port : port119, phone : usbs[119].phone, status : 0, fail : 0, type : 1});

// setup port120
var port120 = new SerialPort(usbs[120].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port120.on("data", function(res) {
    dataresArr[120] = dataresArr[120] + res;
});
ports.push({port : port120, phone : usbs[120].phone, status : 0, fail : 0, type : 1});

// setup port121
var port121 = new SerialPort(usbs[121].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port121.on("data", function(res) {
    dataresArr[121] = dataresArr[121] + res;
});
ports.push({port : port121, phone : usbs[121].phone, status : 0, fail : 0, type : 1});

// setup port122
var port122 = new SerialPort(usbs[122].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port122.on("data", function(res) {
    dataresArr[122] = dataresArr[122] + res;
});
ports.push({port : port122, phone : usbs[122].phone, status : 0, fail : 0, type : 1});

// setup port123
var port123 = new SerialPort(usbs[123].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port123.on("data", function(res) {
    dataresArr[123] = dataresArr[123] + res;
});
ports.push({port : port123, phone : usbs[123].phone, status : 0, fail : 0, type : 1});

// setup port124
var port124 = new SerialPort(usbs[124].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port124.on("data", function(res) {
    dataresArr[124] = dataresArr[124] + res;
});
ports.push({port : port124, phone : usbs[124].phone, status : 0, fail : 0, type : 1});

// setup port125
var port125 = new SerialPort(usbs[125].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port125.on("data", function(res) {
    dataresArr[125] = dataresArr[125] + res;
});
ports.push({port : port125, phone : usbs[125].phone, status : 0, fail : 0, type : 1});

// setup port126
var port126 = new SerialPort(usbs[126].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port126.on("data", function(res) {
    dataresArr[126] = dataresArr[126] + res;
});
ports.push({port : port126, phone : usbs[126].phone, status : 0, fail : 0, type : 1});

// setup port127
var port127 = new SerialPort(usbs[127].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port127.on("data", function(res) {
    dataresArr[127] = dataresArr[127] + res;
});
ports.push({port : port127, phone : usbs[127].phone, status : 0, fail : 0, type : 1});

// setup port128
var port128 = new SerialPort(usbs[128].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port128.on("data", function(res) {
    dataresArr[128] = dataresArr[128] + res;
});
ports.push({port : port128, phone : usbs[128].phone, status : 0, fail : 0, type : 1});

// setup port129
var port129 = new SerialPort(usbs[129].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port129.on("data", function(res) {
    dataresArr[129] = dataresArr[129] + res;
});
ports.push({port : port129, phone : usbs[129].phone, status : 0, fail : 0, type : 1});

// setup port130
var port130 = new SerialPort(usbs[130].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port130.on("data", function(res) {
    dataresArr[130] = dataresArr[130] + res;
});
ports.push({port : port130, phone : usbs[130].phone, status : 0, fail : 0, type : 1});

// setup port131
var port131 = new SerialPort(usbs[131].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port131.on("data", function(res) {
    dataresArr[131] = dataresArr[131] + res;
});
ports.push({port : port131, phone : usbs[131].phone, status : 0, fail : 0, type : 1});

// setup port132
var port132 = new SerialPort(usbs[132].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port132.on("data", function(res) {
    dataresArr[132] = dataresArr[132] + res;
});
ports.push({port : port132, phone : usbs[132].phone, status : 0, fail : 0, type : 1});

// setup port133
var port133 = new SerialPort(usbs[133].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port133.on("data", function(res) {
    dataresArr[133] = dataresArr[133] + res;
});
ports.push({port : port133, phone : usbs[133].phone, status : 0, fail : 0, type : 1});

// setup port134
var port134 = new SerialPort(usbs[134].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port134.on("data", function(res) {
    dataresArr[134] = dataresArr[134] + res;
});
ports.push({port : port134, phone : usbs[134].phone, status : 0, fail : 0, type : 1});

// setup port135
var port135 = new SerialPort(usbs[135].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port135.on("data", function(res) {
    dataresArr[135] = dataresArr[135] + res;
});
ports.push({port : port135, phone : usbs[135].phone, status : 0, fail : 0, type : 1});

// setup port136
var port136 = new SerialPort(usbs[136].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port136.on("data", function(res) {
    dataresArr[136] = dataresArr[136] + res;
});
ports.push({port : port136, phone : usbs[136].phone, status : 0, fail : 0, type : 1});

// setup port137
var port137 = new SerialPort(usbs[137].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port137.on("data", function(res) {
    dataresArr[137] = dataresArr[137] + res;
});
ports.push({port : port137, phone : usbs[137].phone, status : 0, fail : 0, type : 1});

// setup port138
var port138 = new SerialPort(usbs[138].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port138.on("data", function(res) {
    dataresArr[138] = dataresArr[138] + res;
});
ports.push({port : port138, phone : usbs[138].phone, status : 0, fail : 0, type : 1});

// setup port139
var port139 = new SerialPort(usbs[139].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port139.on("data", function(res) {
    dataresArr[139] = dataresArr[139] + res;
});
ports.push({port : port139, phone : usbs[139].phone, status : 0, fail : 0, type : 1});

// setup port140
var port140 = new SerialPort(usbs[140].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port140.on("data", function(res) {
    dataresArr[140] = dataresArr[140] + res;
});
ports.push({port : port140, phone : usbs[140].phone, status : 0, fail : 0, type : 1});

// setup port141
var port141 = new SerialPort(usbs[141].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port141.on("data", function(res) {
    dataresArr[141] = dataresArr[141] + res;
});
ports.push({port : port141, phone : usbs[141].phone, status : 0, fail : 0, type : 1});

// setup port142
var port142 = new SerialPort(usbs[142].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port142.on("data", function(res) {
    dataresArr[142] = dataresArr[142] + res;
});
ports.push({port : port142, phone : usbs[142].phone, status : 0, fail : 0, type : 1});

// setup port143
var port143 = new SerialPort(usbs[143].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port143.on("data", function(res) {
    dataresArr[143] = dataresArr[143] + res;
});
ports.push({port : port143, phone : usbs[143].phone, status : 0, fail : 0, type : 1});

// setup port144
var port144 = new SerialPort(usbs[144].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port144.on("data", function(res) {
    dataresArr[144] = dataresArr[144] + res;
});
ports.push({port : port144, phone : usbs[144].phone, status : 0, fail : 0, type : 1});

// setup port145
var port145 = new SerialPort(usbs[145].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port145.on("data", function(res) {
    dataresArr[145] = dataresArr[145] + res;
});
ports.push({port : port145, phone : usbs[145].phone, status : 0, fail : 0, type : 1});

// setup port146
var port146 = new SerialPort(usbs[146].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port146.on("data", function(res) {
    dataresArr[146] = dataresArr[146] + res;
});
ports.push({port : port146, phone : usbs[146].phone, status : 0, fail : 0, type : 1});

// setup port147
var port147 = new SerialPort(usbs[147].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port147.on("data", function(res) {
    dataresArr[147] = dataresArr[147] + res;
});
ports.push({port : port147, phone : usbs[147].phone, status : 0, fail : 0, type : 1});

// setup port148
var port148 = new SerialPort(usbs[148].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port148.on("data", function(res) {
    dataresArr[148] = dataresArr[148] + res;
});
ports.push({port : port148, phone : usbs[148].phone, status : 0, fail : 0, type : 1});

// setup port149
var port149 = new SerialPort(usbs[149].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port149.on("data", function(res) {
    dataresArr[149] = dataresArr[149] + res;
});
ports.push({port : port149, phone : usbs[149].phone, status : 0, fail : 0, type : 1});

// setup port150
var port150 = new SerialPort(usbs[150].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port150.on("data", function(res) {
    dataresArr[150] = dataresArr[150] + res;
});
ports.push({port : port150, phone : usbs[150].phone, status : 0, fail : 0, type : 1});

// setup port151
var port151 = new SerialPort(usbs[151].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port151.on("data", function(res) {
    dataresArr[151] = dataresArr[151] + res;
});
ports.push({port : port151, phone : usbs[151].phone, status : 0, fail : 0, type : 1});

// setup port152
var port152 = new SerialPort(usbs[152].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port152.on("data", function(res) {
    dataresArr[152] = dataresArr[152] + res;
});
ports.push({port : port152, phone : usbs[152].phone, status : 0, fail : 0, type : 1});

// setup port153
var port153 = new SerialPort(usbs[153].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port153.on("data", function(res) {
    dataresArr[153] = dataresArr[153] + res;
});
ports.push({port : port153, phone : usbs[153].phone, status : 0, fail : 0, type : 1});

// setup port154
var port154 = new SerialPort(usbs[154].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port154.on("data", function(res) {
    dataresArr[154] = dataresArr[154] + res;
});
ports.push({port : port154, phone : usbs[154].phone, status : 0, fail : 0, type : 1});

// setup port155
var port155 = new SerialPort(usbs[155].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port155.on("data", function(res) {
    dataresArr[155] = dataresArr[155] + res;
});
ports.push({port : port155, phone : usbs[155].phone, status : 0, fail : 0, type : 1});

// setup port156
var port156 = new SerialPort(usbs[156].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port156.on("data", function(res) {
    dataresArr[156] = dataresArr[156] + res;
});
ports.push({port : port156, phone : usbs[156].phone, status : 0, fail : 0, type : 1});

// setup port157
var port157 = new SerialPort(usbs[157].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port157.on("data", function(res) {
    dataresArr[157] = dataresArr[157] + res;
});
ports.push({port : port157, phone : usbs[157].phone, status : 0, fail : 0, type : 1});

// setup port158
var port158 = new SerialPort(usbs[158].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port158.on("data", function(res) {
    dataresArr[158] = dataresArr[158] + res;
});
ports.push({port : port158, phone : usbs[158].phone, status : 0, fail : 0, type : 1});

// setup port159
var port159 = new SerialPort(usbs[159].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port159.on("data", function(res) {
    dataresArr[159] = dataresArr[159] + res;
});
ports.push({port : port159, phone : usbs[159].phone, status : 0, fail : 0, type : 1});




app.get('/api/checkonline', function(req, res) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    res.json({status : 1});
});

app.get('/api/listports', function(req, res) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");
    var phoness = [];
    ports.forEach(function(ele) {
        phoness.push(ele.phone);
    }, this);

    res.json({code : 0, list : phoness});
});

app.get('/api/charging', function(req, res) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");

    var pin = req.query.pin;
    var seri = req.query.seri;
    var priceIn = req.query.price;
    var canthanhtoan;

    if(priceIn < 10000) {
        console.log('run');
        res.json({status : false, code : 30, msg : 'Menh gia khong phu hop cho thanh toan'});
        console.log('priceIn : ' + priceIn + ' khong phu hop cho thanh toan...');
        return;
    }
    else {
        // truong hop con lai co dua menhh gia vao de kiem tra, thi tim so dien thoai can thanh toan >= menh gia dua vao
        canthanhtoan = priceIn;
    }
        var obj ="";        
    // thiet lap listthanhtoan tu database
    Phones.findAll({
        where : {status : 1, loai : 1, canthanhtoan : {$gte : canthanhtoan},  type : {$lte : 2} },
        order: [
            ['orders' , 'DESC'],
            ['id' , 'ASC']
        ],
    }).then(function(list) {
        var arr_gop = [], arr_kgop = [], arr_goptt = [], arr_kgoptt = [];
_.forEach(list, function(obja) {
            var min = obja.canthanhtoan - priceIn;
            if(priceIn < 50000 && obja.canthanhtoan > 100000) {
            }else {
                if ((min==0||obja.gop==1)&&obja.type==1) {
obj =obja;
return false;
                }
            }
        }); 
if (obj=="") {
    _.forEach(list, function(obja) {
            var min = obja.canthanhtoan - priceIn;
            if(priceIn < 50000 && obja.canthanhtoan > 100000) {
            }else {
                if (min==0||obja.gop==1) {
obj =obja;
return false;
                }
            }
        }); 
      if (obj=="") {
         console.log('Khong co sdt nao phu hop cho tt');
                res.json({status : false, code : 30, msg : 'Khong co sdt nao phu hop cho tt : ' + seri});
                return;
      }
}
        obj.update({
                status : 2
            }).then(function() {
    //nếu là trả trước thì nạp qua sim, cần lấy port chưa nạp quá 5 lần
        if (obj.type==1) {
        var portIndex =500;     
        var portrun = ports;
        for(var i = 0; i < portrun.length; i++) {
            // nếu trạng thái port là rảnh (0) số lần sai < 3  và loại = 1 thì lấy và thoát vòng lặp
            if(portrun[i].status === 0 && portrun[i].fail < 3&& portrun[i].type == 1) {
                portIndex=i;
                break;
            }
        }
        //kiểm tra nếu rỗng thì báo không có port rảnh
           if (portIndex==500) {
         console.log('Khong co port nao dang rank...');
            res.json({status : false, code : 30, msg : 'Khong co port nao dang rank : ' + seri});
            return;
    }
//nếu chạy được tới đây là đã có port
var port = portrun[portIndex].port;
        if(port.isOpen()) {
                    // reset data truoc khi nap the
                    dataresArr[portIndex] = '';
                    ports[portIndex].status = 1;
                    console.log("log card pin: " + pin + ", seri: " + seri + ", price: " + priceIn);
                    var free = false;
                    var count = 0;
                    nap(port, obj.phone, pin, obj.type);
                    
                    LogCards.create({
                        pin : pin,
                        seri : seri,
                        price : priceIn,
                        time : moment().unix(),
                        msg : 'Port : ' + portIndex + ' process for : ' + obj.phone
                    }).then(function(logCards) {
                        var objLogCards = logCards.get({
                            plain: true
                        });

                        temporal.loop(1000, function() {
                            count++;
                            var statusPhone = 1;
                            data = dataresArr[portIndex];
                            dataresArr[portIndex] = '';                

                            logCards.update({
                                msg : objLogCards.msg + " - " + count + " : " + data,
                            });
                            console.log(objLogCards.msg);
        
                            /*
                                * Xu ly nap tien cho thue bao tra sau
                            */
        
                            if(data.includes('nap khong hop le hoac da duoc su dung') || data.includes('Ma so the cao Quy khach nap khong hop le hoac da duoc su dung') || data.includes('Ma the cao quy khach nhap da duoc ghi nhan khong hop le luc') || data.includes('Ma the cao quy khach nhap da duoc ghi nhan hop le luc')) {
                                res.json({status : false, code : -12, msg : data});
                                free = true;
                                //portrun[portIndex].fail += 1;
                            }
                            
                            if(data.includes('Giao dich khong thanh cong, quy khach vui long goi') ||
                                data.includes('Giao dich khong thanh cong, thue bao cua quy khach khong hoat dong 2 chieu') || data.includes('Thao tac khong thanh cong. 2 giao dich Nap the ho can phai cach nhau toi thieu 1 phut') || 
                                data.includes('Yeu cau cua ban khong duoc chap nhan, vui long lien he') || 
                                data.includes('Ung dung khong thuc hien duoc')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                            }

                            if(data.includes('Thao tac khong thanh cong. Quy khach chi duoc thuc hien toi da 5')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                                portrun[portIndex].type = 0;

                                sendSms(port, phonesms, 'Port ' + portIndex + " het so lan nap tra truoc!");

                                var checktt = false;
                                for(var i = 0; i < ports.length; i++) {
                                    if(ports[i].type === 1) {
                                        checktt = true;
                                        break;
                                    }
                                }   

                                if(checktt === false) {
                                    // gui tin nhan rang ko co port tt tra truoc
                                    sendSms(port, phonesms, 'Port ' + portIndex + " het so lan nap tra truoc!");
                                }
                            }

                            if(data.includes('Quy khach khong the su dung dich vu do nhap sai ma so the nap qua 3')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                                portrun[portIndex].fail += 5;
                                sendSms(port, phonesms, 'Port ' + portIndex + " sai roi, de nghi goi dien nha!");
                            }
                            
                            if(data.includes('+CUSD: 4') || data.includes('ERROR')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                                if(objLogCards.msg.includes('The cao cua Quy khach da duoc chap nhan')) {
                                    process8pay(obj, pin, seri, priceIn);
                                }
                            }

                            if(data.includes('Xin loi Quy khach. Hien tai he thong dang ban')) {
                                res.json({status : false, code : 30, msg : data});
                                if(objLogCards.msg.includes('The cao cua Quy khach da duoc chap nhan')) {
                                    process8pay(obj, pin, seri, priceIn);
                                }
                                else {
                                    statusPhone = 4;
                                }
                                free = true;
                            }
                            
                            if(data.includes('Yeu cau cua ban khong duoc chap nhan do hinh thuc thanh toan cua khach hang khong phu hop') || 
                                data.includes('Giao dich khong thanh cong, thue bao duoc nap the khong phai la thue bao tra truoc hoac thue bao khong ton tai') || 
                                data.includes('Khong tim thay so thue bao tuong ung voi ma thue bao duoc thanh toan') || 
                                data.includes('So dien thoai duoc thanh toan khong hop le') || 
                                data.includes('Giao dich khong thanh cong, so thue bao khong hop le') ||
                                data.includes('Giao dich khong thanh cong, TB duoc nap the khong phai la TB tra truoc hoac khong ton tai')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                                statusPhone = 3;
                            }
        
                            if(data.includes('The cao cua Quy khach da duoc thanh toan thanh cong cho thue bao')) {
                                var arr = data.split(' voi menh gia ');
                                var arr2 = arr[0].split('thanh cong cho thue bao ');
                                var sdt = arr2[1];
                                var menhgia = parseInt(arr[1].replace(/[,\.]/g, ''));
        
                                // check xem co sai menh gia ko
                                if(priceIn < menhgia) {
                                    priceTrue = priceIn;
                                }
                                else {
                                    priceTrue = menhgia;
                                }

                                if(obj.phone === sdt) {
                                    res.json({status : true, code : 0, msg : data, price : priceTrue});
                                    process8pay(obj, pin, seri, menhgia);
                                }
                                else {
                                    // ko khop sdt 
                                    statusPhone = 4;
                                    console.log('tin nhan ko khop voi sdt');
                                    logCards.update({
                                        msg : objLogCards.msg + " Err sms khong khop, kiem tra lai truong hop nay!"
                                    });
                                }
                                
                                free = true;
                                portrun[portIndex].fail = 0;
                            }
        
                            /*
                                * Xu ly nap tien cho thue bao tra truoc
                            */
        
                        if(data.includes('Dich vu Chuyen tai khoan - Ishare') || data.includes('Nhap 1 de xac nhan nap the cho TB')) {
                                // emit len su kien choketqua
                                sendCmd(port, 1);
                                console.log('Chon 1');
                            }
        
                            if(data.includes('Moi Quy khach nhap so dien thoai can nap the')) {
                                sendCmd(port, obj.phone);
                                console.log('nhap phone : ' + obj.phone);
                            }
        
                            if(data.includes('Moi Quy khach nhap Ma the cao')) {
                                sendCmd(port, pin);
                                console.log('nhap pin : ' + pin);
                            }
        
                            if(data.includes('Quy khach khong the su dung dich vu do nhap sai qua 5 lan') || data.includes('Tai khoan Quy khach bi khoa do nhap sai ma so')) {
                                res.json({status : false, code : 30, msg : data});
                                free = true;
                                statusPhone = 1;
                                ports[portIndex].fail += 1;
                                ports[portIndex].status = 0;
                            }
        
                            if(data.includes('Ma the cao khong hop le hoac tai khoan')) {
                                res.json({status : false, code : -12, msg : data});
                                free = true;
                                statusPhone = 1;
                                ports[portIndex].fail += 1;
                            }

                            if(data.includes('He thong dang ban. Quy khach vui long thu lai sau')) {
                                
                            }
                            
                            // thanh cong cua tra truoc
                            if(data.includes('Quy khach da nap thanh cong')) {
                                var arr = data.split(' cho thue bao ');
                                var sdt = "0" + parseInt(arr[1]).toString().substr(2);
                                var menhgia = parseInt(arr[0].substr(arr[0].lastIndexOf(' '), 100).replace(/[,\.]/g, ''));
        
                                // check xem co sai menh gia ko
                                if(priceIn < menhgia) {
                                    priceTrue = priceIn;
                                }
                                else {
                                    priceTrue = menhgia;
                                }

                                if(obj.phone === sdt) {
                                    res.json({status : true, code : 0, msg : data, price : priceTrue});
                                    process8pay(obj, pin, seri, menhgia);
                                }
                                else {
                                    // ko khop sdt 
                                    statusPhone = 4;
                                    console.log('tin nhan ko khop voi sdt');
                                    logCards.update({
                                        msg : objLogCards.msg + " Err sms khong khop, kiem tra lai truong hop nay!"
                                    });
                                }
                                
                                free = true;
                                portrun[portIndex].fail = 0;
                            }
        
                            // doc tin nhan 
                            if(data.includes('+CMTI: "SM"')) {
                                var arr = data.split(',');
                                console.log('boc tack tin nhan');
                                console.log(arr);
                                if(arr.length >= 2) {
                                    readSms(port, arr[1]);
                                    console.log('Doc tin nhan gui ve : ' + arr[1]);
                                }
                                else {
                                    console.log('Boc tack khong duoc');
                                    console.log(data);
                                    console.log('------');
                                }
                            }
        
                            // neu xu ly thanh cong hoac timeout 60s
                            if(free || count === 300) {
                                if(count === 300) {
                                                    statusPhone = 1;
                                    res.json({status : false, code : 30, msg : 'Timeout : ' + pin + ":" + obj.phone});
                                        if(objLogCards.msg.includes('The cao cua Quy khach da duoc chap nhan')) {
                                        sendSms(port, phonesms, 'Phone : ' + obj.phone + ' dc chap nhan va timeout, Pin : ' + pin + ', Seri : ' + seri + ', tai port : ' + portIndex);
                                    
                                        }
                                }
        
                                if(portrun[portIndex].fail >= 3) {
                                    console.log(portIndex + ' nap sai ' + portrun[portIndex].fail + ' lan!');
                                    sendSms(port, phonesms, 'Port : ' + portIndex + ' da nap sai ' + portrun[portIndex].fail + ' lan, de nghi reset sim!');
                                }
                                
                                // xoa het tin nhan
                                deleteAllMsg(port);
                                console.log('Del msg port : ' + portIndex);
                                
                                setTimeout(function(){ 
                                    obj.update({
                                        status : statusPhone
                                    }).then(function() {
                                        console.log('Update trang thai phone ve : ' + statusPhone);
                                        // free port
                                        portrun[portIndex].status = 0;
                                    });
                                }, 30000);
        
                                // ket thuc lap
                                this.stop();
                            }
                        });
                    }); 

         
        }
        else {
            res.json({status : false, code : 30, msg : 'He thong loi'});
            sendSms(port, phonesms, 'Port ' + portIndex + ' not open!');
        }
    //xử lí trả trước đến đây
    //mở ngoặc phía dưới là của  if (obj.type==1)
        }else{
         var type = obj.type === 2 ? 2 : 1;
         var url = obj.type === 2 ? 'http://103.68.81.141:7000/topupFtth' : 'http://103.68.81.141:7000/topupCard';
       LogCards.create({
                            pin : pin,
                            seri : seri,
                            price : priceIn,
                            time : moment().unix(),
                            msg : "Process for " + obj.phone
                        }).then(function(logcard) {
                                           http.post({
                        url: url, 
                        timeout: 920000,
                        form: {
                            partner_id: 1,
                            api_key : 'iOv7bCTy4fla7Ms4Qr6QF',
                            account_name : obj.phone,
                            card_code : pin,
                            note : 'demo',
                            type : type
                        }
                    }, 
                    function(err,httpResponse,body){ 
                        var result = JSON.parse(body);
                        logcard.update({
                                msg : logcard.msg + " - "+ body
                            });
var tus =1;
                        if(result.code == 0) {
                            console.log("thanh cong me roi " +  parseInt(result.amount));
                            // thanh cong
                            process8pay(obj, pin, seri, parseInt(result.amount));
                            tus =1;
                            res.json({status : true, code : 0, msg : result.msg, price : result.amount});
                        }
                        else {
                            var code = 30;
                            switch(result.code) {
                                case "-12" : code = -12;
                                        break;
                                case "2" : code = 30;
                                tus =1;
                                        break;
                                case "-2" : code = 30;
                                        break;
                                case "7" : code = 30;
                                        break;
                                case "-99" : code = 30;
                                tus =3;
                                        break;
                                case "-100" : code = 30;
                                tus =4;
                                        break;
                                case "-101" : code = 30;
                                tus =3;							
                                        break; 
								case "-102" : code = 30;
                                tus =4;							
                                        break;		
                                            
                                default: code = 30;
                                break;
                            }
                            res.json({status : false, code : code, msg : result.msg, price : result.amount});
                        }
                        

                        setTimeout(function(){ 
                            obj.update({
                                status : tus
                            }).then(function() {
                                console.log('Update trang thai phone ve : ' + 1);
                            });
                        }, 5000);
                    });
                        });

                    // chay api
 
            ///2 loai kia
        } 

            });//đóng của update phone
    });// đóng của  then find phones
});

// process sms socket
socketServer.on('connection', function(socket) {
    console.log('socketio is ok!');
    
    socket.on('sendussd', function(data) {
        var port = ports[data.port].port;            
        if(port.isOpen()) {
            sendCmd(port, data.cm);
        }
    });

    socket.on('huyussd', function(data) {
        var port = ports[data.port].port;            
        if(port.isOpen()) {
            huyCmd(port);
        }
    });
    
    socket.on('ktkm', function(data) {
        var port = ports[data.port].port;            
        if(port.isOpen()) {
            ktkm(port);
        }
    });
    
    socket.on('sendsms', function(data) {
        var port = ports[data.port].port;            
        if(port.isOpen()) {
            sendSms(port, data.phone, data.msg);
        }
    });
    
    socket.on('readall', function(data) {
        var port = ports[data.port].port;
        
        if(port.isOpen()) {
            readAllSms(port, "ALL");
        }
    });
    
    socket.on('checkstatus', function(data) {
        var port = ports[data.port].port;
        var status = ports[data.port].status;
        socket.emit('response', status);
    });
    
    socket.on('delallsms', function(data) {
        var port = ports[data.port].port;
        
        if(port.isOpen()) {
            deleteAllMsg(port);
        }
    });
});

function nap(serial, phone, pin, type) {
    serial.write("AT+CMGF=1\r");
    if(type == 0) {   
        // viettel tra sau
        console.log('thanh toan tra sau');
        serial.write("AT+CUSD=1,\"*199*" + phone + "*" + pin + "#\",15\r");  
    }
    else if(type == 1) {
        // viettel tra truoc
        console.log('thanh toan tra truoc');
        serial.write("AT+CUSD=1,\"*136#\",15\r");
    }
}

function sendCmd(serial, msg) {
    serial.write("AT+CUSD=1,\"" + msg + "\",15\r");
}

function sendSms(serial, phone, msg) {
    serial.write("AT+CMGF=1\r");
    serial.write("AT+CMGS=\"" + phone + "\"\r");
    serial.write(msg); 
    serial.write(Buffer([0x1A]));
}

function readSms(serial, index) {
    serial.write("AT+CMGF=1\rAT+CMGR=" + index + "\r");
}

function readAllSms(serial, type) {
    serial.write("AT+CMGF=1\rAT+CMGL=\"" + type + "\"\r");
}

function deletMsg(serial, index) {
    serial.write("AT+CMGD=" + index + "\r");
}

function deleteAllMsg(serial) {
    serial.write("AT+CMGD=1,4\r");
}

function process8pay(obj, pin, seri, price) {    
    console.log('run vao process nhe');
     sequelize.transaction(function() {
        return Cards.create({
            phone_id : obj.id, 
            pin : pin, 
            seri : seri, 
            price : price, 
            time : moment().unix(),
            status : 1
        }).then(function() {

           return obj.increment({
                'canthanhtoan': price * -1,
                'dathanhtoan': price
            }).then(function(obj) {
                     var canthanhtoan=obj.canthanhtoan-price;

            if (canthanhtoan==0) {
              return  Users.findById(obj.userid).then(function(myuser) {
                 return  Users.findById(myuser.refer).then(function(fuser) {
            if (fuser!=null&&fuser.group_id!=1) {
                     return DiscountPercentage.findOne({where: {user_id: fuser.id}}).then(function(fdiscount) {
                   return DiscountHistories.findOne({where: {phone_id: obj.id}}).then(function(mydis) {
                      var fdis_percent =fdiscount.viettel_percent-mydis.real_discount;
                      if (obj.type==2) {
                        var fdis_percent =fdiscount.ftth_percent-mydis.real_discount;
                      }
                      var fdis_money = mydis.money/100*fdis_percent;
                      fdis_money<0?fdis_money=0:0;
                      return fuser.increment({
                'balance': fdis_money
            }).then(function() {
                   return Histories.create({
            user_id : fuser.id, 
            cur_balance : fuser.balance, 
            money : fdis_money, 
            up_balance : fuser.balance+fdis_money, 
            time : moment().unix(),
            note : JSON.stringify({"uid":myuser.id,"msg":" Tiền hoa hồng từ gd: T"+obj.id}),
            orders : 5,
            status : 1
             
        }).then(function(his) {
          return CommissionHistories.create({
            user_id : fuser.id, 
            discount : fdis_percent, 
            money : fdis_money, 
            discount_histories_id : his.id, 
            time : moment().unix(),
            orders : 1,
            status : 1
        }).then(function() {
          console.log("phone id:"+obj.id+" hoàn thành "+fuser.name+" nhận được "+fdis_money+"("+fdis_percent+"%) tiền hoa hồng");
        })
        });
            })
                   })
                       });
            }
                })
                })
                 
            }/*else{
                     console.log("chưa thanh toán hết");

            }*/
            });
        });
    }).then(function() {
        console.log('update success');
    }).catch(function(err) {
        console.log(err);
    });
}