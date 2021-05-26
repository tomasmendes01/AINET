const express = require('express');

const app = express();
const server = require('http').createServer(app);
const users = {}

const io = require('socket.io')(server, {
    cors: { origin: "*" }
});


io.on('connection', (socket) => {
    console.log('connection');

    socket.on('new-user', name => {
        console.log(name, "connected");
        users[socket.id] = name
        socket.broadcast.emit('user-connected', name)
    })


    socket.on('sendChatToServer', (message) => {
        console.log(message);

        // io.sockets.emit('sendChatToClient', message);
        socket.broadcast.emit('sendChatToClient', message);
    });

    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});