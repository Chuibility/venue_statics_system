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

	wVersionRequested = MAKEWORD(1, 1);//��һ������Ϊ��λ�ֽڣ��ڶ�������Ϊ��λ�ֽ�  

	err = WSAStartup(wVersionRequested, &wsaData);//��winsock DLL����̬���ӿ��ļ������г�ʼ����Э��Winsock�İ汾֧�֣��������Ҫ����Դ��  
	if (err != 0)
	{
		return;
	}

	if (LOBYTE(wsaData.wVersion) != 1 || HIBYTE(wsaData.wVersion) != 1)//LOBYTE����ȡ��16���������λ��HIBYTE����ȡ��16��������ߣ�����ߣ��Ǹ��ֽڵ�����        
	{
		WSACleanup();
		return;
	}
	SOCKET sockClient = socket(AF_INET, SOCK_STREAM, 0);

	SOCKADDR_IN addrClt;//��Ҫ���������IP��Ϣ  
	addrClt.sin_addr.S_un.S_addr = inet_addr("127.0.0.1");// inet_addr��IP��ַ�ӵ�����ʽת���������ֽڸ�ʽ���͡�  
	addrClt.sin_family = AF_INET;
	addrClt.sin_port = htons(13000);

	connect(sockClient, (SOCKADDR*)&addrClt, sizeof(SOCKADDR));//�ͻ����������������������  

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

	wVersionRequested = MAKEWORD(1, 1);//��һ������Ϊ��λ�ֽڣ��ڶ�������Ϊ��λ�ֽ�  

	err = WSAStartup(wVersionRequested, &wsaData);//��winsock DLL����̬���ӿ��ļ������г�ʼ����Э��Winsock�İ汾֧�֣��������Ҫ����Դ��  
	if (err != 0)
	{
		return;
	}

	if (LOBYTE(wsaData.wVersion) != 1 || HIBYTE(wsaData.wVersion) != 1)//LOBYTE����ȡ��16���������λ��HIBYTE����ȡ��16��������ߣ�����ߣ��Ǹ��ֽڵ�����        
	{
		WSACleanup();
		return;
	}
	SOCKET sockClient = socket(AF_INET, SOCK_STREAM, 0);

	SOCKADDR_IN addrClt;//��Ҫ���������IP��Ϣ  
	addrClt.sin_addr.S_un.S_addr = inet_addr("192.168.1.101");// inet_addr��IP��ַ�ӵ�����ʽת���������ֽڸ�ʽ���͡�  
	addrClt.sin_family = AF_INET;
	addrClt.sin_port = htons(13000);

	connect(sockClient, (SOCKADDR*)&addrClt, sizeof(SOCKADDR));//�ͻ����������������������  

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