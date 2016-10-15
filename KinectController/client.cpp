#include"stdafx.h"
#include"stdio.h"
#include <strsafe.h>
#include <Winsock2.h>
#include "client.h"
#pragma comment( lib, "ws2_32.lib" )  
#include <d2d1helper.h>
#include <dwrite.h> 
#include <wchar.h>
#define MAXLEN 65535



void transfer(FILE* fp) {
	char buffer[65535];
	WORD wVersionRequested;
	WSADATA wsaData;
	int err;

	wVersionRequested = MAKEWORD(1, 1);//第一个参数为低位字节；第二个参数为高位字节  

	err = WSAStartup(wVersionRequested, &wsaData);//对winsock DLL（动态链接库文件）进行初始化，协商Winsock的版本支持，并分配必要的资源。  
	if (err != 0)
	{
		return;
	}

	if (LOBYTE(wsaData.wVersion) != 1 || HIBYTE(wsaData.wVersion) != 1)//LOBYTE（）取得16进制数最低位；HIBYTE（）取得16进制数最高（最左边）那个字节的内容        
	{
		WSACleanup();
		return;
	}
	SOCKET sockClient = socket(AF_INET, SOCK_STREAM, 0);

	SOCKADDR_IN addrClt;//需要包含服务端IP信息  
	addrClt.sin_addr.S_un.S_addr = inet_addr("127.0.0.1");// inet_addr将IP地址从点数格式转换成网络字节格式整型。  
	addrClt.sin_family = AF_INET;
	addrClt.sin_port = htons(13000);

	connect(sockClient, (SOCKADDR*)&addrClt, sizeof(SOCKADDR));//客户机向服务器发出连接请求  

	int k = 0;
	int p = 0;
	int size;
	

	fgets(buffer, 100, fp);
	send(sockClient, buffer, 100, 0);
	_cwprintf(L"%d", k);
	k++;
	closesocket(sockClient);
	WSACleanup();
}


void transfer(BYTE* buff, DWORD size) {
	WORD wVersionRequested;
	WSADATA wsaData;
	int err;

	wVersionRequested = MAKEWORD(1, 1);//第一个参数为低位字节；第二个参数为高位字节  

	err = WSAStartup(wVersionRequested, &wsaData);//对winsock DLL（动态链接库文件）进行初始化，协商Winsock的版本支持，并分配必要的资源。  
	if (err != 0)
	{
		return;
	}

	if (LOBYTE(wsaData.wVersion) != 1 || HIBYTE(wsaData.wVersion) != 1)//LOBYTE（）取得16进制数最低位；HIBYTE（）取得16进制数最高（最左边）那个字节的内容        
	{
		WSACleanup();
		return;
	}
	SOCKET sockClient = socket(AF_INET, SOCK_STREAM, 0);

	SOCKADDR_IN addrClt;//需要包含服务端IP信息  
	addrClt.sin_addr.S_un.S_addr = inet_addr("192.168.1.101");// inet_addr将IP地址从点数格式转换成网络字节格式整型。  
	addrClt.sin_family = AF_INET;
	addrClt.sin_port = htons(13000);

	connect(sockClient, (SOCKADDR*)&addrClt, sizeof(SOCKADDR));//客户机向服务器发出连接请求  

	char sendBuf[MAXLEN];
	int k = 0;
	while (k * MAXLEN < size) {
		if (MAXLEN*(k + 1) >= size) {
			send(sockClient, (char*)buff + k*MAXLEN, size - MAXLEN*k, 0);
			break;
		}
		send(sockClient, (char*)buff + k*MAXLEN, MAXLEN, 0);
		_cwprintf(L"%d", k);
		k++;
	}
	closesocket(sockClient);
	WSACleanup();
}