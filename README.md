安装
----------------------------------

```bash
# 安装服务
$ composer require firma/swoole-http-bundle
```

命令行操作
----------------------------------

```bash
# 启动swoole 服务
$ php bin/console swoole:server:start
```

```bash
# 关闭swoole 服务
$ php bin/console swoole:server:stop
```

```bash
# 重载swoole 服务
$ php bin/console swoole:server:reload
```
```bash
# swoole 服务状态
$ php bin/console swoole:server:status
```

----------------------------------

配置
----------------------------------
#### 默认配置
- 在 config/packages 下配置 firma_swoole.yaml
```yaml
firma_swoole:
    host: 0.0.0.0
    port: 8000
    options:
        pid_file:  '%kernel.logs_dir%/%kernel.environment%_swoole_server.pid'
        log_file: '%kernel.logs_dir%/swoole.log'
        daemonize: true #守护进程化
        document_root: '%kernel.project_dir%/public' #配置静态文件根目录， https://wiki.swoole.com/wiki/page/783.html
        enable_static_handler: true

```

#### 参数配置

```yaml
options:
    max_request: ~ #设置worker进程的最大任务数，默认为0，一个worker进程在处理完超过此数值的任务后将自动退出，进程退出后会释放所有内存和资源。
    open_cpu_affinity: ~ #启用CPU亲和性设置。在多核的硬件平台中，启用此特性会将swoole的reactor线程/worker进程绑定到固定的一个核上。可以避免进程/线程的运行时在多个核之间互相切换，提高CPU Cache的命中率。                  
    task_worker_num: ~ #配置Task进程的数量，配置此参数后将会启用task功能。所以Server务必要注册onTask、onFinish2个事件回调函数。如果没有注册，服务器程序将无法启动。
    enable_port_reuse: ~ #设置端口重用，此参数用于优化TCP连接的Accept性能，启用端口重用后多个进程可以同时进行Accept操作。
    worker_num: ~ #设置启动的Worker进程数。
    reactor_num: ~ #Reactor线程数 z，reactor_num => 2，通过此参数来调节主进程内事件处理线程的数量，以充分利用多核。默认会启用CPU核数相同的数量。
    dispatch_mode: ~ #数据包分发策略。可以选择3种类型，默认为2
    discard_timeout_request: ~ #swoole在配置dispatch_mode=1或3后，系统无法保证onConnect/onReceive/onClose的顺序，因此可能会有一些请求数据在连接关闭后，才能到达Worker进程。
    open_tcp_nodelay: ~ #启用open_tcp_nodelay，开启后TCP连接发送数据时会关闭Nagle合并算法，立即发往客户端连接。在某些场景下，如http服务器，可以提升响应速度。
    open_mqtt_protocol: ~ #启用mqtt协议处理，启用后会解析mqtt包头，worker进程onReceive每次会返回一个完整的mqtt数据包。
    user: ~ #设置worker/task子进程的所属用户。服务器如果需要监听1024以下的端口，必须有root权限。但程序运行在root用户下，代码中一旦有漏洞，攻击者就可以以root的方式执行远程指令，风险很大。配置了user项之后，可以让主进程运行在root权限下，子进程运行在普通用户权限下。
    group: ~ #设置worker/task子进程的进程用户组。与user配置相同，此配置是修改进程所属用户组，提升服务器程序的安全性。
    #设置SSL隧道加密，设置值为一个文件名字符串，制定cert证书和key私钥的路径。
    ssl_cert_file: ~ 
    ssl_key_file: ~
```

为Redis连接定义Symfony服务，并将其设置为服务的构造函数参数RedisSessionHandler：
```
# config/services.yaml
services:  
    Redis:
        class: Redis
        calls:
            - method: connect
              arguments:
                  - '%env(REDIS_HOST)%'
                  - '%env(int:REDIS_PORT)%'
            # If you need key prefix, uncomment line belows
            # - method: setOption
            #   arguments:
            #       - !php/const Redis::OPT_PREFIX
            #       - 'my_prefix'

    Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler:
        arguments:
            - '@Redis'
```    
要了解有关%env(…)%Symfony 3\4 中引入的高级用法的更多信息（如我在此处使用的int处理器），请查看Symfony文档。
您现在可以将该服务用作会话处理程序：

```
# config/packages/framework.yaml
framework:  
    session:
        handler_id: Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler
```

 - [swoole参数文档][1] 
 
 
 [1]: https://wiki.swoole.com/wiki/page/p-max_request.html
