var http = require("http"),
	socketio = require("socket.io"),
	url = require('url'),
	mime = require('mime'),
	path = require('path'),
	fs = require('fs');


// Listen for HTTP connections.  This is essentially a miniature static file server that only serves our one file, client.html:
var app = http.createServer(function(req, resp){
		// This callback runs when a new connection is made to our HTTP server.
	//fs.readFile("client.html", function(err, data){
	//	// This callback runs when the client.html file has been read from the filesystem.
	//	if(err) return resp.writeHead(500);
	//	resp.writeHead(200);
	//	resp.end(data);
	//});
//this chunk is to get our static images working
	var filename = path.join(__dirname, url.parse(req.url).pathname);
	if(url.parse(req.url).pathname == "/") {
		filename += "client.html";
	}
	(fs.exists || path.exists)(filename, function(exists){
		if (exists) {
			fs.readFile(filename, function(err, data){
				if (err) {
					// File exists but is not readable (permissions issue?)
					resp.writeHead(500, {
						"Content-Type": "text/plain"
					});
					resp.write("Internal server error: could not read file");
					resp.end();
					return;
				}
 
				// File exists and is readable
				var mimetype = mime.lookup(filename);
				resp.writeHead(200, {
					"Content-Type": mimetype
				});
				resp.write(data);
				resp.end();
				return;
			});
		}else{
			// File does not exist
			resp.writeHead(404, {
				"Content-Type": "text/plain"
			});
			resp.write("Requested file not found: "+filename);
			resp.end();
			return;
		}
	});

});
app.listen(3456);
//rooms array holds name, id, and array of active users
 var rooms = [{name:'Default',id:0, hasPass: false, pass: null, activeUsers: [], blockedUsers:[], creator: null}];

// Do the Socket.IO magic:
var io = socketio.listen(app);
io.sockets.on("connection", function(socket){
//function to add user
	socket.on('add_user', function(name){
		socket.username = name;
		socket.room = 'Default';
		socket.join('Default');
		io.emit("refresh_list", rooms);
		for(var room in rooms){
			if(rooms[room].id== socket.room){
				rooms[room].activeUsers.push(socket.username);
			}
		}
	});
//function to move rooms
	socket.on('move_room', function(chatroom, user){
		socket.leave(socket.room);
		var oldroom=null;
		var newroom=null;
		for(var room1 in rooms){
			if(rooms[room1].id== socket.room){
				position = rooms[room1].activeUsers.indexOf(user);
				rooms[room1].activeUsers.splice(position, 1);
				oldroom=rooms[room1].name;
			}
		}
		socket.broadcast.to(socket.room).emit('update', user + ' has left '+ oldroom);
		socket.join(chatroom);
		socket.room = chatroom;
		for(var room in rooms){
			if(rooms[room].id== socket.room){
				rooms[room].activeUsers.push(user);
				newroom=rooms[room].name;
				//io.emit("refresh_users", socket.room);
			}
			console.log(rooms[room].id+" "+rooms[room].activeUsers);
			socket.broadcast.to(rooms[room].id).emit("user_list", rooms[room].activeUsers);
		}
		socket.broadcast.to(socket.room).emit('update', user + ' has joined '+newroom);
		console.log("~");
		//console.log(rooms[socket.room].activeUsers);
		
		//console.log(rooms.name[socket.room].activeUsers);
	});
	
//send message recieved from client to everyone
socket.on('message_to_server', function(data) {
	// This callback runs when the server receives a new message from the client.
		io.sockets.in(socket.room).emit("message_to_client",{s_username:data.c_username, s_message:data.c_message, s_nameColor:data.c_nameColor });
		// broadcast the message to other users
	});
/*
socket.on('message_to_server_update', function(data) {
		// This callback runs when the server receives a new message from the client.
		io.sockets.in(socket.room).emit("message_to_client_update",{s_message:data.c_message, s_nameColor:data.c_nameColor });
		// broadcast the message to other users
	});
*/
//send sticker recieved from client to everyone
socket.on('sticker_sent', function(data){
		io.sockets.in(socket.room).emit("display_sticker", {s_username: data.c_username, s_nameColor: data.c_nameColor, s_stickerID: data.c_stickerID});
	});
socket.on("new_room_created", function(data){
	rooms.push({name: data.room_name, id: data.room_ID, hasPass: data.room_hasPass, pass: data.room_pass, activeUsers:[], blockedUsers: [], creator: data.creator});
	io.emit("refresh_list",rooms);
	io.emit("refresh_users", data.room_ID);
	});
socket.on('verify_pass', function(data){
		success=(data.guess===rooms[data.roomID].pass);
		console.log(success);
		console.log(data.guess);
		console.log(rooms[data.roomID].pass);
		var return_name = rooms[data.roomID].name;
		var return_id = rooms[data.roomID].id;
		console.log(return_name);
		console.log(return_id);
		socket.emit("return_pass", {success: success, room_name: return_name, room_ID:return_id});
	});
	socket.on("kick_user", function (data){
	if(rooms[data.room].creator == socket.username){
	position = rooms[data.room].activeUsers.indexOf(data.blockableUser);
	rooms[data.room].activeUsers.splice(position, 1);
	socket.broadcast.to(data.room).emit('update', data.blockableUser + ' has left this room');
	io.emit("refresh_list", rooms);
	}
	else{
		alert( "only the creator can do that!");
	}
	});
	socket.on("block_user", function (data){
	if(rooms[data.room].creator == socket.username){
	rooms[data.room].blockedUsers.push(data.blockableUser);
	socket.broadcast.to(data.room).emit('update', data.blockableUser + ' has been banned from this room');
	io.emit("refresh_list", rooms);
	}
	else{
		alert( "only the creator can do that!");
	}
	});
		});