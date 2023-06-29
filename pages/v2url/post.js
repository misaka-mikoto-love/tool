        // 生成随机字符串
        function generateRandomString(length) {
            var characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var randomString = '';
            for (var i = 0; i < length; i++) {
                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return randomString;
        }

        // 注册和登录
        async function registerAndLogin() {
            var email = generateRandomString(8) + '@gmail.com';
            var password = generateRandomString(8);

            var registerUrl = "https://www.ckcloud.shop/api/v1/passport/auth/register";
            var registerData = { email: email, password: password };

            try {
                // 注册
                const registerResponse = await fetch(registerUrl, {
                    method: 'POST',
                    body: JSON.stringify(registerData),
                    headers: { 'Content-Type': 'application/json' }
                });

                if (registerResponse.ok) {
                    var loginUrl = "https://www.ckcloud.shop/api/v1/passport/auth/login";
                    var loginData = { email: email, password: password };

                    // 登录
                    const loginResponse = await fetch(loginUrl, {
                        method: 'POST',
                        body: JSON.stringify(loginData),
                        headers: { 'Content-Type': 'application/json' }
                    });

                    if (loginResponse.ok) {
                        const loginData = await loginResponse.json();
                        var token = loginData.data.token;
                        await getContent(token); // 获取内容
                    } else {
                        console.error("Login failed");
                    }
                } else {
                    console.error("Registration failed");
                }
            } catch (error) {
                console.error("Error: " + error);
            }
        }

        // 获取内容
        async function getContent(token) {
            var url = "https://www.jcbb.info/api/v1/client/subscribe?token=" + token;
            var data = { url: url };

            try {
                const response = await fetch('post.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    const content = await response.text();
                    document.getElementById("content").innerHTML = content;
                } else {
                    console.error("Failed to get content");
                }
            } catch (error) {
                console.error("Error: " + error);
            }
        }


        registerAndLogin();