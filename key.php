<?php
$content = "";
session_start();
if ((isset($_SESSION['admin'])) && ($_SESSION['admin'] == true)) {
  if (isset($_POST["message"])) {
    if ($_POST["action"] == "save") {
      $handle = fopen(__DIR__ . "/apikey.php", "w") or die("Writing file failed.");
      if ($handle) {
        fwrite($handle, "<?php header('HTTP/1.1 404 Not Found');exit; ?>\n" . $_POST["message"]);
        fclose($handle);
        exit;
      }
    } elseif ($_POST["action"] == "check") {
      $lines = explode("\n", $_POST["message"]);
      $i = 0;
      $validkey = "";
      $invalidkey = "";
      while ($i < count($lines)) {
        $line = $lines[$i];
        $headers  = [
          'Accept: application/json',
          'Content-Type: application/json',
          'Authorization: Bearer ' . $line
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/models/gpt-3.5-turbo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        $complete = json_decode($response);
        if (isset($complete->error)) {
          $invalidkey .= $line . "\n";
        } else {
          $validkey .= $line . "\n";
        }
        $i++;
      }
      echo $validkey;
      exit;
    }
  }
  $line = 0;
  $handle = @fopen(__DIR__ . "/apikey.php", "r");
  if ($handle) {
    while (($buffer = fgets($handle)) !== false) {
      $line++;
      if ($line > 1) {
        $content .= $buffer;
      }
    }
    fclose($handle);
  }
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>API_KEY配置信息</title>
    <script src="js/jquery-3.6.4.min.js"></script>
    <script src="js/layer.min.js" type="application/javascript"></script>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
      }

      .container {
        margin: 50px auto;
        width: 80%;
        max-width: 800px;
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
      }

      textarea {
        width: 100%;
        height: 200px;
        padding: 10px;
        border: none;
        border-radius: 5px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        resize: none;
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 20px;
      }

      .btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4caf50;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin-right: 10px;
        transition: background-color 0.3s ease;
      }

      .btn:hover {
        background-color: #3e8e41;
      }

      table {
        margin: auto;
        border-collapse: collapse;
        width: 100%;
        max-width: 800px;
      }

      th,
      td {
        padding: 8px;
        text-align: center;
        color: #000000;
        border: 1px solid #000000;
      }

      th:first-child,
      td:first-child {
        width: 30%;
      }

      th:nth-child(2),
      td:nth-child(2) {
        width: 10%;
      }

      th:nth-child(3),
      td:nth-child(3) {
        width: 20%;
      }

      th:nth-child(4),
      td:nth-child(4) {
        width: 10%;
      }

      th:nth-child(5),
      td:nth-child(5) {
        width: 15%;
      }

      th:nth-child(6),
      td:nth-child(6) {
        width: 15%;
      }

      th:nth-child(7),
      td:nth-child(7) {
        width: 10%;
      }

      tr:nth-child(even) {
        background-color: #f1d39c;
      }

      tr:nth-child(odd) {
        background-color: #f1d39c;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <h1>API_KEY配置信息</h1>
      <textarea placeholder="请按一行一回车的方式录入" id="tt"><?php echo $content; ?></textarea>
      <select id="api-url-select">
        <option value="https://api.openai.com">【官方,需科学】 https://api.openai.com</option>
        <option value="https://openai.1rmb.tk">【社区反代】 https://openai.1rmb.tk</option>
        <option value="custom">自定义 ...</option>
      </select>
      <input type="text" id="custom-url-input" placeholder="自定义API链接" style="display: none;">
      <button class="btn" onclick="checkit();">验证有效性</button>
      <button class="btn" onclick="sendRequest();">查询额度</button>
      <button class="btn" onclick="saveit();">保存当前设置</button>
    </div>
    <table id="result-table">
      <thead>
        <tr>
          <th>API KEY</th>
          <th>总额度</th>
          <th>已使用</th>
          <th>剩余额度</th>
          <th>截止日期</th>
          <th>最高模型</th>
          <th>是否绑卡</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <script>
      // 定义查询过的API-KEY列表
      let queriedApiKeys = [];

      async function checkBilling(apiKey, apiUrl) {
        const now = new Date();
        const startDate = new Date(now - 90 * 24 * 60 * 60 * 1000);
        const endDate = new Date(now.getTime() + 24 * 60 * 60 * 1000);

        const urlSubscription = `${apiUrl}/v1/dashboard/billing/subscription`;
        const urlBalance = `${apiUrl}/dashboard/billing/credit_grants`;
        const urlUsage = `${apiUrl}/v1/dashboard/billing/usage?start_date=${form_2atDate(
          startDate
        )}&end_date=${form_2atDate(endDate)}`;

        const headers = {
          Authorization: "Bearer " + apiKey,
          "Content-Type": "application/json",
        };

        try {
          let response = await fetch(urlSubscription, {
            headers
          });
          if (!response.ok) {
            console.log("您的账户已被封禁，请登录OpenAI进行查看。");
            return;
          }
          const subscriptionData = await response.json();
          const totalAmount = subscriptionData.hard_limit_usd;

          response = await fetch(urlUsage, {
            headers
          });
          const usageData = await response.json();
          const totalUsage = usageData.total_usage / 100;

          const remaining = totalAmount - totalUsage;

          const lastDate = new Date(subscriptionData.access_until * 1000);
          const year = lastDate.getFullYear();
          const month = lastDate.getMonth() + 1;
          const day = lastDate.getDate();
          const endDateString = year + "-" + month + "-" + day;

          const hasPaymentMethod = subscriptionData.has_payment_method ? "Yes" : "No";

          console.log(`Total Amount: ${totalAmount.toFixed(2)}`);
          console.log(`Used: ${totalUsage.toFixed(2)}`);
          console.log(`Remaining: ${remaining.toFixed(2)}`);
          console.log(`End Date: ${endDateString}`);

          const modelUrl = `${apiUrl}/v1/models`;
          response = await fetch(modelUrl, {
            headers
          });
          const data = await response.json();
          let gptModels = data.data.filter((model) => model.id.includes("gpt"));
          let highestGPTModel = gptModels.reduce((prev, current) => {
            let prevVersion = parseFloat(prev.id.split("-")[1]);
            let currentVersion = parseFloat(current.id.split("-")[1]);
            return currentVersion > prevVersion ? current : prev;
          });

          return [
            totalAmount,
            totalUsage,
            remaining,
            endDateString,
            highestGPTModel.id,
            hasPaymentMethod,
          ];
        } catch (error) {
          console.error(error);
          return [null, null, null, null, null];
        }
      }

      function form_2atDate(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, "0");
        const day = date.getDate().toString().padStart(2, "0");

        return `${year}-${month}-${day}`;
      }

      function sendRequest() {
        let apiKeyInput = document.getElementById("tt");
        let apiUrlSelect = document.getElementById("api-url-select");
        let customUrlInput = document.getElementById("custom-url-input");

        if (apiKeyInput.value.trim() === "") {
          alert("请填写API KEY");
          return;
        }

        document.querySelector("#result-table tbody").innerHTML = "";

        let apiUrl = "";
        if (apiUrlSelect.value === "custom") {
          if (customUrlInput.value.trim() === "") {
            alert("请设置API链接");
            return;
          } else {
            apiUrl = customUrlInput.value.trim();
          }
        } else {
          apiUrl = apiUrlSelect.value;
        }

        let apiKeys = apiKeyInput.value.trim().split("\n");

        let tableBody = document.querySelector("#result-table tbody");
        apiKeys.forEach((apiKey) => {
          if (queriedApiKeys.includes(apiKey)) {
            console.log(`API KEY ${apiKey} 已查询过，跳过此次查询`);
            return;
          }
          queriedApiKeys.push(apiKey);

          checkBilling(apiKey, apiUrl)
            .then((data) => {
              let row = document.createElement("tr");
              let apiKeyCell = document.createElement("td");
              apiKeyCell.textContent = apiKey.replace(/^(.{7})(.*)(.{6})$/, "$1****************$3");
              row.appendChild(apiKeyCell);

              if (data[0] === null) {
                let errorMessageCell = document.createElement("td");
                errorMessageCell.colSpan = "6";
                errorMessageCell.classList.add("status-error");
                errorMessageCell.textContent = "API请求失败，请检查其有效性或网络情况";
                row.appendChild(errorMessageCell);
              } else {
                let totalGrantedCell = document.createElement("td");
                totalGrantedCell.textContent = data[0].toFixed(2);
                row.appendChild(totalGrantedCell);

                let totalUsedCell = document.createElement("td");
                totalUsedCell.textContent = data[1].toFixed(2);
                row.appendChild(totalUsedCell);

                let totalAvailableCell = document.createElement("td");
                totalAvailableCell.textContent = data[2].toFixed(2);
                row.appendChild(totalAvailableCell);

                let endDateCell = document.createElement("td");
                endDateCell.textContent = data[3];
                row.appendChild(endDateCell);

                let highestGPTModel = document.createElement("td");
                highestGPTModel.textContent = data[4];
                row.appendChild(highestGPTModel);

                let hasPaymentMethod = document.createElement("td");
                hasPaymentMethod.textContent = data[5];
                row.appendChild(hasPaymentMethod);
              }

              tableBody.appendChild(row);

              if (apiKey === apiKeys[apiKeys.length - 1]) {
                queriedApiKeys = [];
              }
            })
            .catch((error) => {
              console.error(error);
              let row = document.createElement("tr");
              let apiKeyCell = document.createElement("td");
              apiKeyCell.textContent = apiKey;
              row.appendChild(apiKeyCell);

              let errorMessageCell = document.createElement("td");
              errorMessageCell.colSpan = "6";
              errorMessageCell.style.width = "90px";
              errorMessageCell.classList.add("status-error");
              errorMessageCell.textContent = "不正确或已失效的API-KEY";
              row.appendChild(errorMessageCell);

              tableBody.appendChild(row);

              if (apiKey === apiKeys[apiKeys.length - 1]) {
                queriedApiKeys = [];
              }
            });
        });
      }

      let apiUrlSelect = document.getElementById("api-url-select");
      let customUrlInput = document.getElementById("custom-url-input");

      apiUrlSelect.addEventListener("change", function() {
        if (apiUrlSelect.value === "custom") {
          customUrlInput.style.display = "inline-block";
          customUrlInput.style.marginTop = "5px";
        } else {
          customUrlInput.style.display = "none";
        }
      });
    </script>
  </body>

  <script>
    function saveit() {
      $.ajax({
        type: "POST",
        url: "key.php",
        data: {
          message: $("#tt").val(),
          action: "save",
        },
        success: function(results) {
          layer.msg("保存成功，您可以刷新本网页确认");
        },
      });
    }

    function checkit() {
      var loading = layer.msg("验证中，这需要一些时间，请稍候...", {
        icon: 16,
        shade: 0.4,
        time: false, //取消自动关闭
      });
      $.ajax({
        type: "POST",
        url: "key.php",
        data: {
          message: $("#tt").val(),
          action: "check",
        },
        success: function(results) {
          $("#tt").val(results);
          layer.close(loading);
          layer.msg("验证完毕，无效API_KEY已被删除，请记得点保存设置。");
        },
      });
    }
  </script>

  </html>

<?php
  exit;
}
// 定义用户名和密码常量 
define('USERNAME', 'admin');
define('PASSWORD', 'admin@2023');
// 判断是否提交了表单
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // 获取表单提交的用户名和密码
  $username = $_POST['username'];
  $password = $_POST['password'];
  // 判断用户名和密码是否正确
  if ($username == USERNAME && $password == PASSWORD) {
    // 登录成功，跳转到首页
    $_SESSION['admin'] = true;
    header("Location: key.php");
    exit;
  } else {
    // 登录失败，显示错误信息
    $error = '用户名或密码错误';
    $_SESSION['admin'] = false;
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>登录页面</title>
  <style>
    body {
      background-color: #f2f2f2;
      font-family: Arial, sans-serif;
      display: flex;
      align-items: center;
      height: 100vh;
    }

    h1 {
      text-align: center;
      color: #333;
    }

    form {
      width: 400px;
      margin: auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    label {
      display: block;
      margin-bottom: 10px;
      color: #333;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      box-sizing: border-box;
      margin-bottom: 20px;
    }

    button[type="submit"] {
      background-color: #333;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    button[type="submit"]:hover {
      background-color: #555;
    }

    p.error {
      color: red;
      margin-top: 10px;
    }
  </style>
</head>

<body>
  <?php if (isset($error)) : ?>
    <script>
      alert('<?php echo $error; ?>');
    </script>
  <?php endif; ?>
  <form method="post">
    <h1>API_KEY管理后台</h1>
    <p> <label>用户名：</label> <input type="text" name="username"> </p>
    <p> <label>密码：</label> <input type="password" name="password"> </p>
    <p style="text-align:center"> <button type="submit">登录</button> </p>
  </form>
</body>

</html>
