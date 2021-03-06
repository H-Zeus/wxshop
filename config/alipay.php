<?php
return [
		//应用ID,您的APPID。
		'app_id' => "2016092500595412",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEAu8xJuPWqL/FRkHJZGXNIvHUmwUmgr1XlL65wdgSzpT8sxRYuo9qDABpdkvvXJTO7zkcru5xrBZT9i6X26w5kuMVtUYL99B5qEhwHScmZpIy//oIVCjPZ+lXkwU1Pk6Pjrk4P2aeiGDNsg5sbmVv1xoLkR64QEb8qCxEvIeJSbNBqkWz4+5vEX47fc/Y+KvDu7yi5v8zbEVwWxXvJ9T2L/XLtOEBy5QVGTOCMF9WYKl6o8yX6nqV+TuSohwourjCAbYykWxQ0zY9Gog2I8TSuHek3KNrUUt936d4Ic2aXjR7CbOeg2rjzQVI6uwYRvGOtDdNDXkW7K7BYEKXZbzWAHQIDAQABAoIBAFL8UxeId0v4NzK9VOIKL3oXyqhfAMJ6EmyfiNKeAURkRkRRKQr+5CSHghIRd2DZG4RrrseYtzIgRGoRTKtSEPAVTdlXKSFWz0hxWkcg7uPnzm+IzANWJlyY2B0TLLbxFQtRM2/aw6YuEyWUxP1fxZuS+40xPaiZ8NozHcC7YfDy5kdCbWxAvZTU3fC+XrmK45gqWKkmcJCRpBQ4Nymlt5U6NtsafXSUKNfBi5UbTtHHrHh/HVJb0MpjhdLVJqjczYIigUHoYvJtQcwjGUJBKlD1RuZN6giW8Xwyv0Ta3lxK0XahJkHKyeW5HqA3n0g98pr5CFOcphn2/DikjpozKAECgYEA4cAEcptrMvzd5szJEmUPk8sk04bp1mko9F6DOl4Mver5NwmV4RWutQFY9O5dBArsZCaTqLIm6bxbbikrZj8gQCuWhV2zDjZDHePDekJFR69kwwjzB1W5lHEhtrt6NEiTVWgYMiZbp6OG/T5ugpCEnCrGQrens5cufkQDsTOfTm0CgYEA1PZjQBfuklOJNAG6eIDGObwT39OgUNRO9M7RbH9092qTEDr1mL+f2ryWRTYjrpjiBN5KRwUqQaLDW0Pj7qOlsWqNtgFBJKggav51SgoRzKvnh/b83y06TstMDwjgeoqdsOU/Mdxht8+NiRKZ06C47HV54SPi0sQOPhTCVzNeKnECgYEAoYIF7JSiuiTNAP2L8TwaS+tvWmJQU5SCt2oSyqRUy18bkzOpZhJkHp1rLjiGlYjXHMO4ql1il6CpbQPJR+prwV0t4BZsLwPFOtIjnQlilWm3Vg3GCX5cOgZCT4CLanJt7hF+Fgr5UdMdlXqgB4srRKsZksvgEA1eNvmkBpffJnUCgYEAzTPgrMiipDKEGS21LRoAZ29Xwyva5Se+MNv8JcymuyThMhltv1KdyJpBPc2OElXUK5a6AbbzYa1hClbfkXn0VYDzrbWFvLmYCyDHiaEsR4g5H+UZAj9FWNlBn5cRHmLk0agD/Zp6OjBvE+5tdOMAbTBBUXRPwoERrDY6kOW5TbECgYB3eqbPCPovHPffdUXjBQ11lkUIAdiZYWCccZ9VQSFM7/rzMdVmArbL5DIV8tTOqriWv7CXH/un68OOJtqToidK6JWsxPMYDXzuAF+PM7NTj9GoX+n1Tg/Ui7ySTxrK0yhwCTvB2gjY+XH+iVGiCNgRCurbmKbfVPywasMlhi3SUg==",
		
		//异步通知地址
		'notify_url' => "http://shop.com/alipay/notify",
		
		//同步跳转
		'return_url' => "http://shop.com/alipay/return",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAmePuNaGfDTuBFi7c5Ty3T7QvvnpGADbnXaZhh4nxt1NcI5fUQT7g2nWZ+7VRs0BoIGpL0hG9RsQ4k7Cd2rSbHO6VpLzwmOcNv2mLKOz8GQj1pJNusal1M8/Z29h1DFFneJ+tmelEMXLB1Gr4HaIc4S354aAj05KckUe08DYEPwTiaaiQgTuTvlXFGhAMwizAuILwptozpYKQaI8zJPh0TtRTrAcfZwlOB//zPNqH76d8BAzjGy01aihLWOikJ+qxYImXAVvOYIgi8KIPWhoy6m7lCC2UBD9sgp2XNmkb9dCS9Eozs3mLd8TVTMqgPVRRMzpyiqrO+LNgTWSWu45b/wIDAQAB",

		//标识沙箱环境
		"mode"=>'dev'
];