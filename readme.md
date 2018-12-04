## 6.11 - 9.15
 ## 基于 laravel vue 的前端模板
 - 系统配置
    - 用户管理
    - 角色管理
    - 日志管理
    - 资源管理

 # 贫穷的知识限制想象力。
 ## 执行、整理、归纳、记录、反思

#### 6.11
- 重写错误异常 

#### 6.13
- 完成表单错误异常统一格式抛出 
    - 修改 Exceptions 中的 Handler.php 的 render 方法
    - method_exists: 检测类中是否包括函数
    - array_first: 获取数组中的第一个元素
    
- 基于 autho2 的api认证与登录 （待完成）
   - 数据填充
   - php artisan make:seeder GoodsTableSeeder
   - php artisan db:seed --class=GoodsTableSeeder
   - Hash::check($password, $user->password)   bcrypt()

-  OAuth2的Passport身认证  (视频学习)
    - 理论
        - 第三方应用程序，又称‘客户端’
        - HTTP服务提供商，简称‘服务提供商’
        - 资源所有者，又称‘用户’
        - 用户代理，就是指浏览器
        - 认证服务器，即服务提供商专门用来处理认证的服务器
        - 资源服务器，即服务提供商存放用户生成的资源的服务器。它与谁服务器，可以是同一台服务器，也可以是不同的服务器
        
    - 密码模式       'password
    - 授权码模式     'code'
    - 简化模式       'token'
    - 客户端模式     
    - 个人密码授权模式    采用这种方式
    
    - php artisan passport:install  会产生两个
        / 个人访问
        / 密码授权
        
    - 配置
        -  Route::group(['middleware' => 'auth:api'], function() {
        })
        
    - $request->validate([
            "password" => "required",
            "password_confirmation" => "required|same:password"   // 确认密码
        ])
        
    - 注册
    
    - 登录
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return $response()->json(['message' => '授权不成功'])
        }
        
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        
        if($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        
        $responseData = [
            'access_token' => $tokenResult->access_tokenToken,
            'token_type' => 'Bearer',
            'expired_at' => Carbon::parse($tokenResult->token->expires_at)->toDayDateTimeString()
        ];
        
       
   - 用户信息
   return $request->user();
   
   
   - 登出
   $request->user()->token()->revoke();
   
   - 异常
    - 如果出现
     （file:///laravel/storage/oauth-private.key" does not exist or is not readable on Passport）
     php artisan passport:install
     
#### 6.14
   - 因每登录一次都会记录一条信息，通过写事件，来删除过期和无效的记录。
   - laravel 定义事件、事件监听器以及触发
   - 并把token做键用户信息等为值，存储到redis中  (未完成)
   - 为接口展示做准备（学习）
   
#### 6.15
   - 代码同步到 linux (完成)
   
   
#### 6.19
   - 菜单数据、前端页面
   - dist文件上传
   
#### 6.20
   - dist文件上传（完成）
    - 上传压缩文件、解压到指定目录
    - $zip = new \ZipArchive();
   
#### 6.21
  - php artisan event:generate 自动生成事件和监听器
  - 登录日志 （完成）
  - 操作日志 （完成）
  - 菜单管理，添加、编辑（完成）
  
  
#### 6.22
  - toDoList

#### crm
    - 初审一组
    - 重建一组
    - 优化一组
    - 审核一组
   
   # 流程
      - dicom审核
      - 分配数据
      - 数据重建
      - 重建数据审核
      - 重建数据优化
      - 重建数据终审
      - 上传文件
      - 完成