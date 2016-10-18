using System;
using System.Net.Sockets;
using System.IO;
using System.Threading.Tasks;
using System.Collections.Generic;
using System.Json;
using System.Text;

namespace ImageToMetaData
{
    class Program
    {
        static string key = "2b83e4bd95f943ef8acfecb58ca11441";
        static string apiUrlTemplate = "https://api.projectoxford.ai/face/v1.0/detect?returnFaceId={0}&returnFaceLandmarks={1}&returnFaceAttributes={2}";
        private static string apiUrlGenerate(bool returnFaceId, bool returnFaceLandmarks, string returnFaceAttributes)
        {
            return string.Format(apiUrlTemplate, returnFaceId.ToString().ToLower(), returnFaceLandmarks.ToString().ToLower(), returnFaceAttributes);
        }
        static string serverUrl = "http://121.201.8.23:8088/image/add";

        static HttpWriter apiUploader = new HttpWriter();
        static HttpWriter jsonUploader = new HttpWriter();
        static MyTcpListener listener = new MyTcpListener();

        /*private static string jsonAddIndex(string json)
        {
            string stringFront = @"{'faces':", stringRear = "}";
            return stringFront + json + stringRear;
        }*/

        private static List<string> separateJsonArray(string json)
        {
            JsonValue vals = JsonValue.Parse(json);
            StringBuilder builder = new StringBuilder();
            List<string> jsonList = new List<string>();
            return jsonList;
        }

        private static Task imageClientToJson(TcpClient client, int taskNo)
        {
            return Task.Run(() =>
            {
                Stream bmpStream = MyTcpListener.CopyFromClient(client);
                Console.WriteLine("Task number {0} TCP finished, {1} bytes retrieved", taskNo, bmpStream.Length);

                long quality = 30;
                Stream jpgStream = BmpStreamToJpegStream.Converter(bmpStream, quality);
                Console.WriteLine("Task number {0} BMP to JPG finished, compressed to {1} bytes with {2} quality.", taskNo, jpgStream.Length, quality);

                string json = apiUploader.postStream(
                    apiUrlGenerate(true, false, "age,gender,smile"),
                    jpgStream, key);
                Console.WriteLine("Task number {0} HTTP request finished.", taskNo);
                Console.WriteLine("Json acquired: \n {0} \n", json);

                string jsonRet = jsonUploader.postJson(serverUrl, json);
                Console.WriteLine("Task number {0} JSON uploaded.", taskNo);
                Console.WriteLine("Server reply: {0}", jsonRet);
            });
        }

        private static async void asyncSteps(TcpClient client, int taskNo)
        {
            await imageClientToJson(client, taskNo);
        }

        public static void Main()
        {
            Random rand = new Random();
            while (true)
            {
                TcpClient client = listener.Listen();
                asyncSteps(client, rand.Next());
            }
        }
    }
    
    
}
