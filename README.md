# GrandFatherPies Parser
Web application for searching queries in the database and parser

## Link
See how the parser works: <http://grandfatherpies.team/>

## File description 
### ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) `index.php`
> Home page of the app. A search query is sent here

### ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) `search.php`
> Search results are displayed here. You can also send a search query on the same page

### ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) `parser.php`
> Here the query search is dynamically processed. You can also activate the parser **springerMetaInfo** or **cyberLeninkaMetaInfo** 

### ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) `aws.php`
> Here is a class that allows you to connect to the database and find data in a table

### ![#f03c15](https://placehold.it/15/f03c15/000000?text=+) `settingAWS.php`
> Here you can find the settings for searching and connecting to [AWS](https://aws.amazon.com/)
1. Before using executable you must get **an access key ID** and **a secret access key** from your administrator in Amazon IMA and ask for following rights:
+ ListTables
+ DescribeTable
+ CreateTable
+ DeleteItem
+ DeleteTable
+ PutItem
2. If you want to upload PDF files to S3, ask your administrator for theese rights:
+ ListBucket
+ CreateBucket
+ ListAllMyBuckets
+ DeleteBucket
+ PutObject
+ GetObject
+ DeleteObject
3. Find out database region

## Folders
### ![#c5f015](https://via.placeholder.com/15/c5f015/000000?text=+) `path`
> Here you can find packages for connecting to **Amazon Web Services** and the **Semantic UI** framework

## Other application 
### ![#1589F0](https://via.placeholder.com/15/1589F0/000000?text=+) `springerMetaInfo.exe` or `springerMetaInfo.elf`
> The parser in language **Golang**. .elf for **OS Linux** and .exe for **OS Windows**. Information about this application is located on this repository: [Click](https://github.com/akmubi/springerMetaParserUltraDoNotUse)
