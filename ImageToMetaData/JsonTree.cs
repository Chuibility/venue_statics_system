using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Json;

namespace ImageToMetaData
{
    class JsonTree
    {
        JsonValue jsonVal;
        public JsonTree(string json)
        {
            jsonVal = JsonValue.Parse(json);
            var jsonObj = jsonVal[0];
        }
    }
}
