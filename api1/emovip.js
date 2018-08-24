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
var SerialPort = require('serialport');
var temporal = require('temporal');
var phonesms = '01675918683';
const Op = Sequelize.Op;
var sequelize = new Sequelize('sim', 'root', 'luyen@123', {
    host: 'localhost',
    dialect: 'mysql',
    logging: true,
    pool: {
        max: 10,
        min: 0,
        idle: 5000
    }
});
var app = express();
var server = app.listen(30000, function() {
    console.log('Server is runing...ok')
});
var socketServer = io(server);
var AutoCards = sequelize.define('AutoCards', {
    time: Sequelize.INTEGER,
    orders: Sequelize.INTEGER,
    status: Sequelize.INTEGER
}, {
    timestamps: false
});
var Phones = sequelize.define('Phones', {
    phone: Sequelize.STRING,
    loai: Sequelize.INTEGER,
    type: Sequelize.INTEGER,
    userid: Sequelize.INTEGER,
    canthanhtoan: Sequelize.INTEGER,
    dathanhtoan: Sequelize.INTEGER,
    gop: Sequelize.INTEGER,
    last_balance: Sequelize.INTEGER,
    time: Sequelize.INTEGER,
    orders: Sequelize.INTEGER,
    status: Sequelize.INTEGER
}, {
    timestamps: false
});
socketServer.on('connection', function(socket) {
    console.log('socketio is ok!');
    var checkConnect = false;
    socket.on('on', function(body) {
        console.log('on');
        checkConnect = false;
        temporal.loop(10000, function() {
            AutoCards.findOne().then(function(card) {
                var cardTime = card.time;
                Phones.findAll({
                    where: {
                        time: {
                            $lte: moment().unix() - cardTime * 60
                        },
                        status: 1,
                        loai: 1,
                        canthanhtoan: {
                            $gte: 10000
                        },
                        type: card.orders
                    }
                }).then(function(phones) {
                    console.log('Can chay : ' + phones.length);
                    if (phones.length > 0) {
                        console.log('chay nap : ' + phones.length);
                        http('http://123.17.153.33/auto/charging.php', function(error, response, body) {
                          /*  console.log(body)*/
                        })
                    }
                })
            });
            if (checkConnect) {
                this.stop()
            }
        })
    });
    socket.on('off', function(body) {
        console.log('off');
        checkConnect = true
    })
})