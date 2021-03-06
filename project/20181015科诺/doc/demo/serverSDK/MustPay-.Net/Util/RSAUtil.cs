﻿using System;
using System.Text;
using System.Security.Cryptography;
using System.Web;
using System.IO;
using System.ComponentModel;

namespace mustpay.Util
{
    /// <summary>
    /// RSA签名工具类。
    /// </summary>
    public class RSAUtil
    {
        /// <summary>
        /// 建议直接使用<code>MustpaySignature.RSASign(data, privateKeyPem)</code>
        /// </summary>
        /// <param name="data"></param>
        /// <param name="privateKeyPem"></param>
        /// <returns></returns>
        public static string RSASign(string data, string privateKeyPem, string charset)
        {
            return MustpaySignature.RSASign(data, privateKeyPem, charset,"RSA");
        }

    }
}

