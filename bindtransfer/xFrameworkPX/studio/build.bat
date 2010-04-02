type "src\app\Console.js" > pxstudio-debug.js
type "src\app\Namespace.js" >> pxstudio-debug.js
type "src\app\App.js" >> pxstudio-debug.js
type "src\ux\Ext.extender.js" >> pxstudio-debug.js
type "src\ux\JSLoader.js" >> pxstudio-debug.js
type "src\ux\Phantom.js" >> pxstudio-debug.js
type "src\widgets\Viewport.js" >> pxstudio-debug.js

java -jar compiler.jar --js=pxstudio-debug.js --js_output_file=pxstudio.js