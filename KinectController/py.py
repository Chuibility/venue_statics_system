__author__ = 'dbshch'
#!/usr/bin/env python
# -*- coding:utf8 -*-

import socket
import sys

class getPic():
    def tcpclient(self, st):
        clientSock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        clientSock.connect(("127.0.0.1", 13000))
        print("D:\\new\\"+st)

        



        file = open("D:\\new\\"+st, 'rb')
        snd = file.read(65535)
        #print(snd)
        while snd:
            sendDataLen = clientSock.send(snd)
            #print(sendDataLen)
            snd = file.read(65535)
        file.close()
        clientSock.close()
        print('finish')

if __name__ == "__main__":
    st = sys.argv[1]
    netClient = getPic()
    netClient.tcpclient(st)