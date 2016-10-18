using System;
using System.Net.Sockets;
using System.IO;
using System.Threading.Tasks;
using System.Collections.Generic;
using System.Json;
using System.Text;
using System.Text.RegularExpressions;

namespace ImageToMetaData
{
    class Program
    {
        static string key = "b49e3b9e25d0460692a826e53e21c840";
        static string apiUrlTemplate = "https://api.projectoxford.ai/face/v1.0/detect?returnFaceId={0}&returnFaceLandmarks={1}&returnFaceAttributes={2}";
        private static string apiUrlGenerate(bool returnFaceId, bool returnFaceLandmarks, string returnFaceAttributes)
        {
            return string.Format(apiUrlTemplate, returnFaceId.ToString().ToLower(), returnFaceLandmarks.ToString().ToLower(), returnFaceAttributes);
        }
        static string serverUrl = "http://121.201.8.23:8088/image/add";

        static HttpWriter apiUploader = new HttpWriter();
        static HttpWriter jsonUploader = new HttpWriter();
        static MyTcpListener listener = new MyTcpListener();

        static List<string> faceList = new List<string>();


        public static bool isSameFace(string id1, string id2)
        {
            string url = "https://api.projectoxford.ai/face/v1.0/verify";
            string jsonTemplate = "{\"faceId1\":\"{0}\", \"faceId2\":\"{1}\"}";
            string json = jsonTemplate.Replace("{0}", id1).Replace("{1}", id2);
            string result = jsonUploader.postJson(url, json, key);
            Console.WriteLine(result);
            if (result.IndexOf("false") == -1)
                return true;
            return false;
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

                MatchCollection matches = Regex.Matches(json, @"""faceId"":""(.*?)""");
                Group grp = null;
                string faceId = null;
                string foundFaceId = null;
                foreach (Match match in matches)
                {
                    grp = match.Groups[1];
                    faceId = grp.ToString();
                    if ((foundFaceId = faceList.Find((string s) => isSameFace(s, faceId))) != null)
                    {
                        Console.WriteLine("FaceId {0} is identical with {1}", faceId, foundFaceId);
                        json = json.Replace(faceId, "");
                    }
                    else
                    {
                        Console.WriteLine("FaceId {0} uploaded", faceId);
                        faceList.Add(faceId);
                    }
                }
                Console.WriteLine("Json after modification: \n {0} \n", json);
                string jsonRet = jsonUploader.postJson(serverUrl, json, "0");
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