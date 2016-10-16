using System.IO;
using System.Net.Http;
using System.Net.Http.Headers;

namespace ImageToMetaData
{
    class HttpWriter
    {
        public string postStream(string actionUrl, Stream upstream, string key)
        {
            using (var client = new HttpClient())
            {
                HttpContent streamContent = new StreamContent(upstream);
                streamContent.Headers.ContentType = new MediaTypeWithQualityHeaderValue("application/octet-stream");
                streamContent.Headers.Add("Ocp-Apim-Subscription-Key", key);
                var response = client.PostAsync(actionUrl, streamContent).Result;
                return response.Content.ReadAsStringAsync().Result;
            }
        }

        public string postJson(string actionUrl, string json)
        {
            using (var client = new HttpClient())
            {
                HttpContent jsonContent = new StringContent(json);
                jsonContent.Headers.ContentType = new MediaTypeWithQualityHeaderValue("application/json");
                var response = client.PostAsync(actionUrl, jsonContent).Result;
                return response.Content.ReadAsStringAsync().Result;
            }
        }
    }
}
