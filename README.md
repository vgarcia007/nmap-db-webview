# nmap-db-webview
## what is it?
The program consists of 3 docker containers. One scans your network with nmap every 20 minutes and stores the information in a MYSQL database (also a container). Another container provides the frontend. Here all collected information is beautifully presented.
![nmap-db-webview screenshot 1](screenshots/list1.jpg?raw=true "nmap-db-webview screenshot 1")
![nmap-db-webview screenshot 2](screenshots/list2.jpg?raw=true "nmap-db-webview screenshot 2")

## install
1. Build image with <code>make</code>
2. Edit env settings <code>DB_PASSWORD, MYSQL_PASSWORD, APP_SECRET, NMAP_NET</code> in <code>docker-compose.yml </code>
3. Start it <code>docker-compose up -d </code>
4. Open <code>http://your-dockerhost:5036/setup</code> in your browser to set up database and get your username and password
5. Open <code>http://your-dockerhost:5036</code> in your browser to see the list of found network elements.


## additional config (if you like :)
You can add fancy icons (64px*64px) to <code>./app/public/img/logos/ </code>  
To match those icons edit <code>./app/private/config-icons.php </code>  
(See examples in <code>./app/private/config-icons.php </code> )