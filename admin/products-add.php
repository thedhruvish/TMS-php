<?php 
$pageTitle = "Add Product";
require_once './include/header-admin.php';
require_once './include/sidebar-admin.php';
?>

    <style>
        /* Main Layout */
        .layout-px-spacing {
            padding: 0 15px;
        }
        .middle-content {
            padding: 0 15px;
        }
        
        /* Form Section Styling */
        .ecommerce-create-section {
            padding: 20px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        /* Form Input Styling with consistent left spacing */
        .form-group {
            margin-bottom: 20px;
            padding-left: 10px; /* Added left spacing */
        }
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
            padding-left: 5px; /* Added for alignment */
        }
        
        /* Text Editor */
        .editor-container {
            margin-bottom: 20px;
            padding-left: 10px; /* Added left spacing */
        }
        #description-editor {
            min-height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 0 0 4px 4px;
            outline: none;
        }
        
        /* Editor Toolbar */
        .editor-toolbar {
            background: #f5f5f5;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
            display: flex;
            gap: 5px;
        }
        .toolbar-btn {
            background: none;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .toolbar-btn:hover {
            background: #e0e0e0;
        }
        .toolbar-btn.active {
            background: #4361ee;
            color: white;
        }
        
        /* Upload Section */
        .upload-container {
            margin-bottom: 25px;
            padding-left: 10px; /* Added left spacing */
        }
        .upload-area {
            border: 2px dashed #ccc;
            padding: 30px;
            text-align: center;
            border-radius: 6px;
            background-color: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s;
        }
        .upload-area:hover {
            border-color: #4361ee;
        }
        .upload-text {
            margin-bottom: 15px;
            color: #666;
        }
        .browse-btn {
            background-color: #4361ee;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            display: inline-block;
            font-size: 14px;
        }
        #file-upload {
            display: none;
        }
        
        /* Right Sidebar */
        .sidebar-section {
            padding-left: 15px;
        }
        .sidebar-section .widget-content {
            padding: 20px;
            background: #fff;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .switch-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-left: 5px; /* Consistent with main content */
        }
        
        /* Button Styling */
        .btn-success {
            background-color: #4361ee;
            border: none;
            padding: 10px 20px;
            font-size: 15px;
            width: 100%;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-success:hover {
            background-color: #3a56d4;
        }
    </style>
</head>
<body>

    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row mb-4 layout-spacing layout-top-spacing">
                
                <!-- Main Content Column -->
                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="widget-content widget-content-area ecommerce-create-section">
                        <!-- Product Name Field -->
                        <div class="form-group">
                            <label for="product-name">Product Name</label>
                            <input type="text" class="form-control" id="product-name" placeholder="Enter product name">
                        </div>

                        <!-- Description Editor -->
                        <div class="editor-container">
                            <label>Description</label>
                            <div class="editor-toolbar">
                                <button type="button" class="toolbar-btn" data-command="bold" title="Bold">
                                    <strong>B</strong>
                                </button>
                                <button type="button" class="toolbar-btn" data-command="italic" title="Italic">
                                    <em>I</em>
                                </button>
                                <button type="button" class="toolbar-btn" data-command="underline" title="Underline">
                                    <u>U</u>
                                </button>
                            </div>
                            <div id="description-editor" contenteditable="true" placeholder="Write product description..."></div>
                        </div>

                        <!-- Upload Section -->
                        <div class="upload-container">
                            <label>Upload Images</label>
                            <div class="upload-area" id="upload-area">
                                <p class="upload-text">Drag & Drop your files or <span class="browse-btn">Browse</span></p>
                                <input type="file" id="file-upload" multiple>
                            </div>
                        </div>

                        <!-- Display Publicly Toggle -->
                        <div class="form-group text-center">
                            <div class="switch-container">
                                <input class="switch-input" type="checkbox" id="showPublicly" checked>
                                <label for="showPublicly">Display publicly</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar Column -->
                <div class="col-xxl-3 col-xl-12 col-lg-12 col-md-12 col-sm-12 sidebar-section">
                    <div class="widget-content widget-content-area ecommerce-create-section">
                        <div class="switch-container">
                            <input class="switch-input" type="checkbox" id="in-stock">
                            <label for="in-stock">In Stock</label>
                        </div>
                        
                        <div class="form-group">
                            <label for="proCode">Product Code</label>
                            <input type="text" class="form-control" id="proCode">
                        </div>
                        
                        <div class="form-group">
                            <label for="proSKU">Product SKU</label>
                            <input type="text" class="form-control" id="proSKU">
                        </div>
                        
                        <div class="form-group">
                            <label for="stock-quantity">Stock Quantity (in meters)</label>
                            <input type="number" class="form-control" id="stock-quantity">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender">
                                <option value="">Choose...</option>
                                <option value="men">Men</option>
                                <option value="women">Women</option>
                                <option value="kids">Kids</option>
                                <option value="unisex">Unisex</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category">
                                <option value="">Choose...</option>
                                <option value="electronics">Electronics</option>
                                <option value="clothing">Clothing</option>
                                <option value="organic">Organic</option>
                                <option value="apparel">Apparel</option>
                                <option value="accessories">Accessories</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <input type="text" class="form-control" id="tags">
                        </div>
                        
                        <div class="form-group">
                            <label for="regular-price">Regular Price</label>
                            <input type="text" class="form-control" id="regular-price">
                        </div>
                        
                        <div class="form-group">
                            <label for="sale-price">Sale Price</label>
                            <input type="text" class="form-control" id="sale-price">
                        </div>
                        
                        <div class="switch-container">
                            <input class="switch-input" type="checkbox" id="pricing-includes-taxes">
                            <label for="pricing-includes-taxes">Price includes taxes</label>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-success">Add Product</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Rich Text Editor Functionality
            const editor = document.getElementById('description-editor');
            const toolbarBtns = document.querySelectorAll('.toolbar-btn');
            
            toolbarBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const command = this.dataset.command;
                    document.execCommand(command, false, null);
                    
                    // Toggle active state
                    toolbarBtns.forEach(b => b.classList.remove('active'));
                    if (document.queryCommandState(command)) {
                        this.classList.add('active');
                    }
                });
            });
            
            // File upload functionality
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('file-upload');
            const browseBtn = document.querySelector('.browse-btn');
            
            // Handle browse button click
            browseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                fileInput.click();
            });
            
            // Handle drag and drop
            ['dragover', 'dragleave', 'drop'].forEach(event => {
                uploadArea.addEventListener(event, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });
            
            uploadArea.addEventListener('dragover', function() {
                this.style.borderColor = '#4361ee';
                this.style.backgroundColor = '#f0f4ff';
            });
            
            uploadArea.addEventListener('dragleave', function() {
                this.style.borderColor = '#ccc';
                this.style.backgroundColor = '#f9f9f9';
            });
            
            uploadArea.addEventListener('drop', function(e) {
                this.style.borderColor = '#ccc';
                this.style.backgroundColor = '#f9f9f9';
                
                if(e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    console.log(fileInput.files.length + ' file(s) selected');
                }
            });
            
            // Handle file selection
            fileInput.addEventListener('change', function() {
                if(this.files.length) {
                    console.log(this.files.length + ' file(s) selected');
                }
            });
        });
    </script>

<?php require_once './include/footer-admin.php'; ?>