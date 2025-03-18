# News Tech Block

## Overview
The News Tech Block plugin adds a Gutenberg block to display tech or WordPress-related news from NewsAPI. This dynamic block fetches articles via a PHP render callback, supports pagination, and leverages a custom REST request with a proper User-Agent header.

## Installation
1. Copy the `news-block` folder into your `wp-content/plugins/` directory.
2. Activate the plugin via the WordPress admin Dashboard under **Plugins**.

## Configuration
1. **API Key:**  
   To securely define your NewsAPI key, add the following line to your `wp-config.php`:
   ```php
   define( 'NEWS_TECH_BLOCK_API_KEY', 'your-api-key-here' );
   ```
   *Note:* A fallback key is provided for development purposes but should be removed in production. Please obtain your own API key from [NewsAPI](https://newsapi.org/).

2. **User-Agent Header:**  
   The plugin sends a custom User-Agent header with each API request. Adjust it in `news-tech-block.php` if needed.

## Usage
1. **Adding the Block:**  
   In the Gutenberg editor, add the "Tech News" block from the Widgets category.
2. **Block Editing:**  
   The editor displays a preview fetched via JavaScript. This preview is powered by `block.js`.
3. **Front-End Display:**  
   When the page is saved, the block's PHP render callback (in `news-tech-block.php`) displays news articles along with pagination controls.
4. **Pagination:**  
   The block limits articles to 5 per page by default. Use the provided Previous/Next links to navigate between pages.

## Customization
- **Query Parameters:**  
  Modify the search query or pagination settings in the PHP file (`news-tech-block.php`) to adjust the results.
- **Styling:**  
  Customize appearances by editing `style.css`.
- **Editor Behavior:**  
  Adjust block behavior in the editor by modifying `block.js`.
- **Change the News Subject:**  
  To change the subject of news articles, open the file `news-tech-block.php` and locate the `q` parameter in the API URL. For instance, change:
  
  ```php
  'q' => 'wordpress',
  ```
  
  to your desired subject:
  
  ```php
  'q' => 'your-subject-here',
  ```
  
  Update the subject as needed and save the file.

## Troubleshooting
- If the block shows "No articles available" or an error message, verify the API key and inspect the API response.
- Ensure your server’s PHP configuration supports SSL. The plugin disables SSL verification for remote requests; update as necessary.
- Review the browser console and WordPress debug logs for additional error details.

## Support & Contributions
If you encounter issues or have suggestions, please consult the plugin documentation. 

For bug reports or feature requests, please open an issue on GitHub.

Thank you for using the News Tech Block plugin!

