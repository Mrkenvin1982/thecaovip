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
var phonesms = "01675918683";
var svPort = '29996';

var sequelize = new Sequelize('sim', 'root', 'luyen@123', {
    host: 'localhost',
    dialect: 'mysql',
    logging: false,
    pool: {
        max: 10,
        min: 0,
        idle: 5000
    },
});

var Phones = sequelize.define('Phones', {
    phone : Sequelize.STRING,
    loai : Sequelize.INTEGER,
    type : Sequelize.INTEGER,
    userid : Sequelize.INTEGER,
    //user_8pay : Sequelize.INTEGER,
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

var app = express();
var server = app.listen(svPort, function() {
    console.log('Server is runing...ok');
});
var socketServer = io(server);

function getRandomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

var usbs = [
    {port : "/dev/ttyUSB17", phone : '01652180688', type : 1},
    {port : "/dev/ttyUSB18", phone : '01652180688', type : 1},
    {port : "/dev/ttyUSB19", phone : '01652180688', type : 1},
    {port : "/dev/ttyUSB20", phone : '01652180688', type : 1},
    {port : "/dev/ttyUSB21", phone : '01652180688', type : 1},
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
    console.log(res);
    dataresArr[0] = dataresArr[0] + res;
    socketServer.emit('response', res);
});
ports.push({port : port0, phone : usbs[0].phone, status : 0, fail : 0});

// setup port 1
var port1 = new SerialPort(usbs[1].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port1.on("data", function(res) {
    dataresArr[1] = dataresArr[1] + res;
    socketServer.emit('response', res);
});
ports.push({port : port1, phone : usbs[1].phone, status : 0, fail : 0});

// setup port 2
var port2 = new SerialPort(usbs[2].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port2.on("data", function(res) {
    dataresArr[2] = dataresArr[2] + res;
    socketServer.emit('response', res);
});
ports.push({port : port2, phone : usbs[2].phone, status : 0, fail : 0});

// setup port 3
var port3 = new SerialPort(usbs[3].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port3.on("data", function(res) {
    dataresArr[3] = dataresArr[3] + res;
    socketServer.emit('response', res);
});
ports.push({port : port3, phone : usbs[3].phone, status : 0, fail : 0});

// setup port 4
var port4 = new SerialPort(usbs[4].port, {
    baudRate: 115200,
    autoOpen : true,
    lock : true,
    parser: SerialPort.parsers.readline('\n')
});
port4.on("data", function(res) {
    dataresArr[4] = dataresArr[4] + res;
    socketServer.emit('response', res);
});
ports.push({port : port4, phone : usbs[4].phone, status : 0, fail : 0});

app.get('/api/charging', function(req, res) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header("Access-Control-Allow-Headers", "Origin, X-Requested-With, Content-Type, Accept");

    var pin = req.query.pin;
    var seri = req.query.seri;
    var priceIn = req.query.price;
    var canthanhtoan;

    if(priceIn < 10000) {
        console.log('run');
        res.json({status : false, code : -8, msg : 'Menh gia khong phu hop cho thanh toan'});
        console.log('priceIn : ' + priceIn + ' khong phu hop cho thanh toan...');
        return;
    }
    else {
        // truong hop con lai co dua menhh gia vao de kiem tra, thi tim so dien thoai can thanh toan >= menh gia dua vao
        canthanhtoan = priceIn;
    }
                
    // thiet lap listthanhtoan tu database
    Phones.findAll({
        where : {status : 1, loai : 3, canthanhtoan : {$gte : canthanhtoan},  type : {$lte : 1} },
        order: [
            ['id' , 'ASC']
        ],
    }).then(function(list) {
        var arr_gop = [], arr_kgop = [];
        _.forEach(list, function(obj) {
            var min = obj.canthanhtoan - priceIn;
            if(obj.gop === 0 && min === 0) {
                arr_kgop.push(obj);
            }
            else {
                arr_gop.push(obj);
            }
        });
        
        if(arr_gop.length === 0 && arr_kgop.length === 0) {
            // can phai nap lai va chuyen kenh
            console.log('Khong co sdt nap phu hop cho thanh toan...');
            res.json({status : false, code : 30, msg : 'Khong co sdt nap phu hop cho thanh toan : ' + seri});
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
        
        var obj;
        if(arr_kgop.length > 0) {
            obj = arr_kgop[0];
            console.log('xu ly nap khong gop cho phone : ' + obj.phone);
        }
        else {
            // sap sep 
            arr_gop.sort(function(obj1, obj2) {
                return obj1.canthanhtoan - obj2.canthanhtoan;
            });
            obj = arr_gop[0];
            console.log('xu ly nap gop cho phone : ' + obj.phone);
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
                        console.log(data);
                        
                        logCards.update({
                            msg : objLogCards.msg + " - " + count + " : " + data,
                        });
    
                        if(data.includes('nap khong hop le hoac da duoc su dung') || data.includes('Ma so the cao Quy khach nap khong hop le hoac da duoc su dung') || data.includes('Ma the cao quy khach nhap da duoc ghi nhan khong hop le luc') || data.includes('Ma the cao quy khach nhap da duoc ghi nhan hop le luc') || data.includes('Ma the khong hop le, vui long thu lai')) {
                            res.json({status : false, code : -12, msg : data});
                            free = true;
                            portrun[portIndex].fail += 1;
                        }
                        
                        if(data.includes('Giao dich khong thanh cong, quy khach vui long goi') || data.includes('Thao tac khong thanh cong. Quy khach chi duoc thuc hien toi da 5') || data.includes('Giao dich khong thanh cong, thue bao cua quy khach khong hoat dong 2 chieu') || data.includes('Thao tac khong thanh cong. 2 giao dich Nap the ho can phai cach nhau toi thieu 1 phut') || data.includes('Yeu cau cua ban khong duoc chap nhan, vui long lien he')) {
                            res.json({status : false, code : 30, msg : data});
                            free = true;
                        }
                        
                        if(data.includes('+CUSD: 4') || data.includes('ERROR') || data.includes('Xin loi Quy khach. Hien tai he thong dang ban')) {
                            res.json({status : false, code : 30, msg : data});
                            free = true;
                            statusPhone = 4;
                        }
                        
                        if(data.includes('Yeu cau cua ban khong duoc chap nhan do hinh thuc thanh toan cua khach hang khong phu hop') || data.includes('Giao dich khong thanh cong, thue bao duoc nap the khong phai la thue bao tra truoc hoac thue bao khong ton tai') || data.includes('Khong tim thay so thue bao tuong ung voi ma thue bao duoc thanh toan') || data.includes('So dien thoai duoc thanh toan khong hop le') || data.includes('Thue bao khong hop le') || data.includes('Giao dich khong thanh cong, TB duoc nap the khong phai la TB tra truoc hoac khong ton tai')) {
                            res.json({status : false, code : 30, msg : data});
                            free = true;
                            statusPhone = 3;
                        }
    
                        /*
                            * Xu ly nap tien cho thue bao tra truoc
                        */
    
                       if(data.includes('1-Nap tien ho.') || data.includes('Bam 1 de dong y,0 de')) {
                            // emit len su kien choketqua
                            sendCmd(port, 1);
                            console.log('Chon 1');
                        }
    
                        if(data.includes('Moi quy khach nhap SDT:')) {
                            sendCmd(port, obj.phone);
                            console.log('nhap phone : ' + obj.phone);
                        }
    
                        if(data.includes('Moi quy khach nhap ma the cao')) {
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

                        // thanh cong cua tra truoc
                        if(data.includes('Quy khach da nap thanh cong')) {
                            var arr = data.split(" vnd vao tai khoan thue bao ");
                            var arr1 = arr[0].split("Quy khach da nap thanh cong ");
                            var arr2 = arr[1].split(". Cam on quy khach da su dung");
                            var sdt = arr2[0];
                            var menhgia = parseInt(arr1[1].replace(/[,\.]/g, ''));
    
                            // check xem co sai menh gia ko
                            if(priceIn < menhgia) {
                                priceTrue = priceIn;
                            }
                            else {
                                priceTrue = menhgia;
                            }

                            if(obj.phone === sdt) {
                                res.json({status : true, code : 0, msg : data, price : priceTrue});
                                process8pay(obj, pin, seri, priceTrue);
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
                        if(free || count === 60) {
                            if(count === 60) {
                                // chuyen huong nap ben kia
                                res.json({status : false, code : 30, msg : 'Timeout : ' + pin + ":" + obj.phone});
                                sendSms(port, phonesms, 'Phone : ' + obj.phone + ' timeout, Pin : ' + pin + ', Seri : ' + seri + ', tai port : ' + portIndex);
                                statusPhone = 4;
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
                            }, 10000);
    
                            // ket thuc lap
                            this.stop();
                        }
                    });
                });  
            }).catch(function(err) {
                console.log(err);
                res.json({status : false, code : 30, msg : data});
            });
        }
        else {
            res.json({status : false, code : 30, msg : data});
            sendSms(port, phonesms, 'Port ' + portIndex + ' not open!');
        }
    });
});

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
    serial.write("AT+CUSD=1,\"*103#\",15\r");
    console.log('thanh toan tra truoc');
}

function sendCmd(serial, msg) {
    serial.write("AT+CUSD=1,\"" + msg + "\",15\r");
}

function huyCmd(serial, msg) {
    serial.write("AT+CUSD=2\r");
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
            });
        });
    }).then(function() {
        console.log('update success');
    }).catch(function(err) {
        console.log(err);
    });
}