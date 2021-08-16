=== Twenty Twenty-One Child ===
Contributors: Saema Miftah
Requires at least: 5.3
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This theme adds some extra capabilities for Rest API requests.

1. Post with its details -> Post ID, Post Title, Contributors ID, Contributors Name, Contributors Email


URL : http://your-domain/wp-json/twentytwentyone-child/v1/latest-posts
Request Parameters : None
Method : GET
Response : [
    {
        "Post ID": 8,
        "Post Title": "Christopher Robin",
        "Contributors": [
            {
                "Contributors ID": "4",
                "Contributors Name": "christopher robin",
                "Contributors Email": "christopher@gmail.com"
            },
            {
                "Contributors ID": "1",
                "Contributors Name": "saema",
                "Contributors Email": "saemamiftah@gmail.com"
            }
        ]
    },
    {
        "Post ID": 5,
        "Post Title": "Winnie The Pooh",
        "Contributors": [
            {
                "Contributors ID": "2",
                "Contributors Name": "pooh bear",
                "Contributors Email": "pooh@gmail.com"
            },
            {
                "Contributors ID": "1",
                "Contributors Name": "saema",
                "Contributors Email": "saemamiftah@gmail.com"
            },
            {
                "Contributors ID": "3",
                "Contributors Name": "tigger",
                "Contributors Email": "tigger@gmail.com"
            }
        ]
    }
]

2. Individual Contributor with their contributor id, contributor email, contributor name, posts id

URL : http://your-domain/wp-json/twentytwentyone-child/v1/contributors/contributor-slug
Request Parameters : Contributer's slug
Method : GET
Response : {
    "Contributors ID": "4",
    "Contributors Name": "christopher robin",
    "Contributors Email": "christopher@gmail.com",
    "Post ID": "8,"
}


3. Creating a post by passing the Contributors ID, Contributors Email, Contributor's name, Post Title, Post Content - if you are a legitimate user.

For creating the posts we have used the Json Basic Auth plugin.
Please download it from the following site (https://github.com/WP-API/Basic-Auth) and add to your plugins folder and activate it from plugins admin menu.

URL : http://your-domain/wp-json/twentytwentyone-child/v1/create_posts
Post Parameters : post_title : "your post title", post_content: "your post content", contributors_name: "your contributors name"
					contributors_id : "your contributors id", contributors_email : "your contributors email"

Authorization type should be Basic
For authentication purposes, please provide your username and password for the wp account
Method : POST
Response : {"Post ID":108,"Status":"Post Created Successfully"}

For creating the post, if the contributors id does not exist then the post will not be created and the folloiwng response will be returned
Response: {"Post ID":0,"Status":"Invalid Contributor Id"}

if incorrect user auth details are provide the following response

Response: {"code":"incorrect_password","message":"<strong>Error<\/strong>: The password you entered for the /password <strong>pooh<\/strong> is incorrect. <a href=\"http:\/\/localhost\/wordpress\/wp-login.php?action=lostpassword\">Lost your password?<\/a>","data":null}

== Installation ==

To use this plugin, prerequisites -> install the plugin - display authors for posts plugin
1. In your admin panel, go to Appearance -> Themes and click the 'Add New' button.
2. Click on upload theme button and upload this theme.
3. Activate this theme "twenty twenty one - child"
4. Once activated, open postman (extension of chrome) for primary check. 
5. enter the url provided and make the requests. Do not forget to add your own domain.


== Frequently Asked Questions ==
 
= Do I need to install any other plugin to make it work =
 
Yes, For creating the posts we have used the Json Basic Auth plugin.
Please download it from the following site (https://github.com/WP-API/Basic-Auth) and add to your plugins folder and activate it from plugins admin menu.

 
= Where do I reach out incase of any issue =
 
Kindly mail your queries to "saemamiftah@gmail.com"
 
 
