using System;
using System.IO;
using System.Net;
using System.Net.Sockets;

namespace ImageToMetaData
{
    class MyTcpListener
    {
        TcpListener server = null;

        // Set listening port on 13000.
        Int32 port = 13000;

        public MyTcpListener()
        {
            try
            {
                // Create listening server.
                IPAddress localAddr = IPAddress.Parse("0.0.0.0");
                server = new TcpListener(localAddr, port);
            }
            catch (SocketException e)
            {
                Console.WriteLine("SocketException: {0}", e);
            }
        }

        public TcpClient Listen()
        {
            try
            {
                // Start listening for client requests.
                server.Start();

                // Wait here until connect establishes.
                Console.WriteLine("Waiting for a connection on port {0}...", port);

                // Perform a blocking call to accept requests.
                // You could also user server.AcceptSocket() here.
                TcpClient client = server.AcceptTcpClient();
                Console.WriteLine("Connected to {0}!", client.Client.RemoteEndPoint);
                return client;
            }
            catch (SocketException e)
            {
                Console.WriteLine("SocketException: {0}", e);
                return null;
            }
        }

        public static Stream CopyFromClient(TcpClient client)
        {
            // Get a stream object for reading and writing
            NetworkStream stream = client.GetStream();
            /*byte[] buffer = new byte[512];
            int i;
            while ((i = stream.Read(buffer, 0, 512)) != 0)
            {
                foreach (byte b in buffer)
                    Console.Write(b);
                Console.WriteLine();
            }*/
            MemoryStream localStream = new MemoryStream();
            stream.CopyTo(localStream);
            localStream.Seek(0, SeekOrigin.Begin);

            // Send back a response.
            byte[] msg = System.Text.Encoding.ASCII.GetBytes("Finished.");
            stream.Write(msg, 0, msg.Length);

            // Close down connection.
            client.Close();

            return localStream;
        }
    }

}
