# Movement-of-Robot
 ### The general idea of ​​the code is to create a system that can control robots remotely using a database to store and retrieve the last movement of the robot.

 #### First code (PHP):
  - This code creates a connection to a MySQL database named `robot_control`.
- It successfully connects to the database. If the connection fails, it displays an error message.
- The code queries the last movement made by the robot through multiplexes for the `direction` field from the `movements` table in the database, where the results are sorted by identifier (`id`) in descending order to retrieve the last movement.
- If there is a movement recorded in the database, it performs its options; however, it displays a message indicating that no movement has been recorded yet.
- Finally, the connection to the database is closed.

#### Second code (Arduino): 
- This code is written in Arduino/C++ and is displayed on an ESP32 module or similar module to connect to a Wi-Fi network.
- The code connects to a Wi-Fi network using the SSID and advanced password.
- After connecting to the network, it executes the HTTP GET execution to the PHP server (the first code) to get the last recorded movement of the robot.
- If the request is successful (i.e. the access code 200 is received from the server), it prints the received in the serial screen.
- If the request fails, it prints an error message on the request value.
- The systematic code repeats every 5 immediately, so does anyone make a new request to the scientists to get updates on the robot's movement.

The provided code is written in PHP and aims to connect to the MySQL database and retrieve the last movement recorded in the "movements" table. I will explain the code step by step:

### First code (PHP)
#### 1. Prepare database connection information:
    php
    $servername = "localhost"
    $username = "root"
    $password = ""
    $dbname = "robot_control"
- `localhost`: The name of the server on which the database is hosted.

- `root`: The user name of the database.
- `""`: password for the database (in this case, there is no password).

- `robot_control`: The name of the database to connect to.

#### 2. Create a connection to the database:
    php
    $conn = new mysqli
    ($servername, $username, $password, $dbname);
- A new connection is created using the `mysqli` object.

#### 3. Check connection:
    php
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
- It is checked if there is a connection error. If there is an error, an error message is displayed and execution is stopped using `die`.

#### 4. Retrieve the last recorded movement:
    php
    $sql = "SELECT direction FROM movements ORDER BY id DESC LIMIT 1"
    $result = $conn->query($sql);
- An SQL query is prepared to retrieve the `direction` field from the `movements` table, and the results are arranged in descending order by column `id`, so only one row
(`LIMIT 1`) is selected.

#### 5. Select the default text if there are no recorded movements:
    php
    $last_movement = "No movements recorded yet"

#### 6. Ensure there are results:
    php
    if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_movement = $row['direction'];
    }
- If there are results, the first row of results is fetched and the value of `direction` is set as the last recorded movement.

#### 7. Close the connection to the database:
    php
    $conn->close();

#### 8. Display the last recorded movement:
    php
    echo $last_movement;
- The value of `last_movement` is displayed, whether it is the last movement or the default text if there are no recorded movements.

### Second code (Arduino)
This code is written in Arduino and aims to connect to a WiFi network, then make an HTTP GET request to a web server to get data from the previously described PHP script. I will explain the code step by step:

#### 1. Include Libraries:

    #include <HTTPClient.h>
    #include <WiFi.h>
- `HTTPClient.h`: A library for handling HTTP requests.

- `WiFi.h`: Library for connecting to WiFi networks.

#### 2.Definition of network variables:

    const char* ssid = "nawaf"
    const char* pass = "0561533776"
    const char* serverUrl = "http://172.20.10.2/robot_control/last_movement.php" // Replace with your server URL

- `ssid`: The name of the WiFi network.
- `pass`: WiFi network password.
- `serverUrl`: The URL of the server hosting the PHP script.

#### 3. Set up your WiFi connection:

    void setup()
    Serial.begin(115200);
    WiFi.begin(ssid, pass);
    while (WiFi.status() != WL_CONNECTED)
    delay(1000);
    Serial.println("Connecting to WiFi...");

- `Serial.begin(115200)`: Starting a serial connection with a speed of 115200 bps.
- `WiFi.begin(ssid, pass)`: Begin trying to connect to a WiFi network using the specified `ssid` and password.

- `while (WiFi.status() != WL_CONNECTED)`:
A loop that keeps repeating until it successfully connects to the WiFi network, with a 1-second delay between each attempt, and a "Connecting to WiFi..." message printed on the serial monitor.

#### 4. Implementation of the workshop:

    void loop()
    // Make an HTTP GET request
    HTTPClient http;
    http.begin(serverUrl);

    int httpResponseCode = http.GET();
    if (httpResponseCode == 200)
    String response = http.getString();
    Serial.print("Received data: ");
    Serial.println(response);
    } else {
    Serial.print("Error fetching data. HTTP response code: ");
    Serial.println(httpResponseCode);
    }

    http.end();

    delay(5000); // Fetch data every 5 seconds
    }

- `HTTPClient http`: Create a new `HTTPClient` object.

- `http.begin(serverUrl)`: Start the connection to the address specified in `serverUrl`.

- `int httpResponseCode = http.GET()`: Send a GET request and store the HTTP response code in the `httpResponseCode` variable.

- `if (httpResponseCode == 200)`: Checks if the response was successful (`200` is the successful response code).

- `String response = http.getString()`: Fetch the response body and store it in the `response` variable.

- `Serial.print("Received data: "); Serial.println(response);`: Prints the received data on the serial monitor.

- `else`: In case there is an error in the response:

- `Serial.print("Error fetching data. HTTP response code: ");

Serial.println(httpResponseCode);`: Prints an error message with the response code.

- `http.end()`: Ends the HTTP connection.

- `delay(5000)`: A 5-second delay before the loop repeats, which means an HTTP request is made every 5 seconds.


