import JSEncrypt from 'jsencrypt/bin/jsencrypt.min'

// 密钥对生成 http://web.chacuo.net/netrsakeypair

const publicKey = 'MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAMUSuqwFFH7Bd5OlriLmhecLwgrbu5BB\n' +
  'inWV0IB5p8TTKpT9xTWPRVvYWcV527KQ3QfggXPdHHUK8w9ezD2fG2sCAwEAAQ=='

const privateKey = 'MIIBVgIBADANBgkqhkiG9w0BAQEFAASCAUAwggE8AgEAAkEAxRK6rAUUfsF3k6Wu\n' +
  'IuaF5wvCCtu7kEGKdZXQgHmnxNMqlP3FNY9FW9hZxXnbspDdB+CBc90cdQrzD17M\n' +
  'PZ8bawIDAQABAkEAqbuo1VGGxmlg0OSop1x+RXGmDW4VDHi2hPGkY5LkASsOvPbU\n' +
  'uLMcsD/r2uoM2SsNaoLXf2joCnABHnH95O7AuQIhAPBXxzCuAVnKs48FkK1xwUtg\n' +
  '2HRJhUT0EAuEii9xXcKfAiEA0elWcn+CZN1CJQEvQg7nYtu6GgbQy0ZdUeLUhS60\n' +
  '37UCIQCLF8HHgmx1ssHH+8iHGFZtcVbTtdjS1wySHdp7KnjAsQIgFB/FrOZyjs7z\n' +
  'h26spC2fm0ereNFMdSCC09XmP/pxVr0CIQC0CIwJqhJFISJ+K3+qEe8tRmiuBYtv\n' +
  'IuMgo3+kHDAIWg=='

// 加密
export function encrypt(txt) {
  const encryptor = new JSEncrypt()
  encryptor.setPublicKey(publicKey) // 设置公钥
  return encryptor.encrypt(txt) // 对数据进行加密
}

// 解密
export function decrypt(txt) {
  const encryptor = new JSEncrypt()
  encryptor.setPrivateKey(privateKey) // 设置私钥
  return encryptor.decrypt(txt) // 对数据进行解密
}

