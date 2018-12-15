<?php
 // Создание сокета, домен,тип и протокол
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
 // Посылаем открытый запрос с заголовками
$headers = "GET /Scraping HTTP/1.1\r\n" .
	"Host: localhost\r\n" .
	"\r\n";
 // Проверяем соединение
if (socket_connect($socket, '127.0.0.1', 80)) {
	 // Записываем сокет и заголовки
	socket_write($socket, $headers);
	 // Записываем ответ от сервера и выводим его
	while ($data = socket_read($socket, 1024)) {
		echo $data;
	}
}