using System.Drawing;
using System.Drawing.Imaging;
using System.IO;

namespace ImageToMetaData
{
    static class BmpStreamToJpegStream
    {
        private static ImageCodecInfo GetEncoder(ImageFormat format)
        {
            ImageCodecInfo[] codecs = ImageCodecInfo.GetImageDecoders();

            foreach (ImageCodecInfo codec in codecs)
            {
                if (codec.FormatID == format.Guid)
                {
                    return codec;
                }
            }
            return null;
        }

        public static MemoryStream Converter(Stream inStream, long quality)
        {
            Bitmap bmp1 = new Bitmap(inStream);
            ImageCodecInfo jpgEncoder = GetEncoder(ImageFormat.Jpeg);
            Encoder myEncoder = Encoder.Quality;

            EncoderParameters myEncoderParameters = new EncoderParameters();

            EncoderParameter myEncoderParameter = new EncoderParameter(myEncoder,
                quality);
            myEncoderParameters.Param[0] = myEncoderParameter;

            MemoryStream outStream = new MemoryStream();
            bmp1.Save(outStream, jpgEncoder, myEncoderParameters);
            outStream.Seek(0, SeekOrigin.Begin);
            return outStream;
        }
    }
}
