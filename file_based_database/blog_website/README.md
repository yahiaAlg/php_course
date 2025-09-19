```
blog/
│
├── classes/
│   ├── seeds.css
│   └── FileBlog.php
│
├── css/
│   ├── home.css
│   ├── about.css
│   ├── contact.css
│   ├── authors.css
│   ├── author-detail.css
│   └── post-detail.css
├── includes/
│   ├── header.php
│   └── footer.php
│
├── about.php
├── contact.php
├── posts.php
├── post_detail.php
├── authors.php
├── author_detail.php
└── index.php

```


```mermaid
graph TD
    A[index.php] --> B[posts.php]
    A --> C[authors.php]
    B --> D1[post_detail.php]
    B --> D2[...]
    B --> DN[post_detail.php]
    C --> E1[author_detail.php]
    C --> E2[...]
    C --> EN[author_detail.php]
    D1 --> F1[comment 1]
    D1 --> F2[comment 2]
    D1 --> FN[comment N]
    DN --> G1[comment 1]
    DN --> G2[comment 2]
    DN --> GM[comment M]

```