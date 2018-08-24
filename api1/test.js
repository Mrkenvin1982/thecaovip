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
];
var ports = [];
var dataresArr = [];

// setup port 0
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

// setup port 1
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

// setup port 2
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

// setup port 3
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


// setup port 4
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

// setup port 5
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


// setup port 6
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


// setup port 7
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

// setup port 8
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

// setup port 9
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

// setup port 10
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

// setup port 11
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

// setup port 12
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

// setup port 13
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

// setup port 14
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

// setup port 15
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
                
    // thiet lap listthanhtoan tu database
    Phones.findAll({
        where : {status : 1, loai : 1, canthanhtoan : {$gte : canthanhtoan},  type : {$lte : 1} },
        order: [
            ['orders' , 'DESC'],
            ['id' , 'ASC']
        ],
    }).then(function(list) {
        var arr_gop = [], arr_kgop = [], arr_goptt = [], arr_kgoptt = [];
        _.forEach(list, function(obj) {
            var min = obj.canthanhtoan - priceIn;
            if(priceIn < 200000 && obj.canthanhtoan > 490000) {
                // ko add vao
                console.log(obj.canthanhtoan);
            }
            else {
                if(obj.type == 0) { // cho vao ds tra sau
                    // cho vao ko gop
                    if(obj.gop === 0 && min === 0) {
                        arr_kgop.push(obj);
                    }

                    // cho vao gop
                    if(obj.gop === 1) {
                        arr_gop.push(obj);
                    }
                }
                else {
                    // cho vao ds tra truoc
                    // cho vao ko gop
                    if(obj.gop === 0 && min === 0) {
                        arr_kgoptt.push(obj);
                    }

                    // cho vao gop
                    if(obj.gop === 1) {
                        arr_goptt.push(obj);
                    }
                }
            }
        });
        
        if(arr_gop.length === 0 && arr_kgop.length === 0 && arr_goptt.length === 0 && arr_kgoptt.length === 0) {
            // can phai nap lai va chuyen kenh
            console.log('Khong co sdt nap phu hop cho thanh toan...');
            res.json({status : false, code : 30, msg : 'Khong co sdt nap phu hop cho thanh toan : ' + priceIn});
            return;
        }
        
        // check port is free
        var portIndex = '';
        var portIndexArr = [];

        var portrun = ports;
        for(var i = 0; i < portrun.length; i++) {
            if(portrun[i].status === 0 && portrun[i].fail < 3) {
                portIndexArr.push(i);
            }
        }   
        
        if(portIndexArr.length === 0) {
            // nap lai va chuyen kenh
            console.log('Khong co port nao dang rank...');
            res.json({status : false, code : 30, msg : 'Khong co port nao dang rank : ' + seri});
            return;
        }
        
        // random port
        portIndex = portIndexArr[getRandomInt(0, portIndexArr.length - 1)];
        console.log('Port : ' + portIndex + ' dang xu ly!');
        var port = portrun[portIndex].port;
        
        var obj, obj1, obj2;

        // check object nay la tra sau hay tra truoc
        if(portrun[portIndex].type == 0) { // chi co the tt tra sau
            if(arr_kgop.length == 0 && arr_gop.length == 0) {
                // nap lai va chuyen kenh
                console.log('Khong co sdt nao phu hop cho tt');
                res.json({status : false, code : 30, msg : 'Khong co sdt nao phu hop cho tt : ' + seri});
                return;
            }

            if(arr_kgop.length > 0 && arr_gop.length == 0) {
                obj = arr_kgop[0];
                console.log('xu ly nap ko gop cho phone : ' + obj.phone);
            }
    
            if(arr_kgop.length == 0 && arr_gop.length > 0) {
                obj = arr_gop[0];
                console.log('xu ly nap gop cho phone : ' + obj.phone);
            }
    
            if(arr_gop.length > 0 && arr_kgop.length > 0) {
                obj1 = arr_kgop[0];
                obj2 = arr_gop[0];
    
                if(obj1.time <= obj2.time) {
                    obj = obj1;
                    console.log('xu ly nap ko gop cho phone : ' + obj.phone);
                }
                else {
                    obj = obj2;
                    console.log('xu ly nap gop cho phone : ' + obj.phone);
                }
            }
        }
        else {
            // chon tra truoc
            if(arr_kgoptt.length == 0 && arr_goptt.length == 0) {
                // nap lai va chuyen kenh
                console.log('Khong co sdt nao phu hop cho tt');
                res.json({status : false, code : 30, msg : 'Khong co sdt nao phu hop cho tt : ' + seri});
                return;
            }     

            if(arr_kgoptt.length > 0 && arr_goptt.length == 0) {
                obj = arr_kgoptt[0];
                console.log('xu ly nap ko gop cho phone : ' + obj.phone);
            }
    
            if(arr_kgoptt.length == 0 && arr_goptt.length > 0) {
                obj = arr_goptt[0];
                console.log('xu ly nap gop cho phone : ' + obj.phone);
            }
    
            if(arr_goptt.length > 0 && arr_kgoptt.length > 0) {
                obj1 = arr_kgoptt[0];
                obj2 = arr_goptt[0];
    
                if(obj1.time <= obj2.time) {
                    obj = obj1;
                    console.log('xu ly nap ko gop cho phone : ' + obj.phone);
                }
                else {
                    obj = obj2;
                    console.log('xu ly nap gop cho phone : ' + obj.phone);
                }
            }
        }

        if(port.isOpen()) {
            // cap nhat trang thai xu ly
            obj.update({
                status : 2
            }).then(function() {
                // reset data truoc khi nap the
                dataresArr[portIndex] = '';
                portrun[portIndex].status = 1;

                var free = false;
                var count = 0;
                nap(port, obj.phone, pin, obj.type);

                console.log("log card pin: " + pin + ", seri: " + seri + ", price: " + priceIn);

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
                        if(free || count === 120) {
                            if(count === 120) {
                                res.json({status : false, code : 30, msg : 'Timeout : ' + pin + ":" + obj.phone});
                                    if(objLogCards.msg.includes('The cao cua Quy khach da duoc chap nhan')) {
                                        process8pay(obj, pin, seri, priceIn);
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
            }).catch(function(err) {
                console.log(err);
                res.json({status : false, code : 30, msg : 'He thong loi'});
            });
        }
        else {
            res.json({status : false, code : 30, msg : 'He thong loi'});
            sendSms(port, phonesms, 'Port ' + portIndex + ' not open!');
        }
    });
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
                      var fdis_money = mydis.money/100*fdis_percent;
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