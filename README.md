# jirai
An azuriom plugin to manage issues and changelogs

Note : An issue can be either a bug or a suggestion

# Disclaimer
This is a very very first release of a plugin that aim to give ability 
to server administrators to manage their bugs, suggestions and changelogs in a better way
I would appreciate pull request either to improve code quality or improve / add some feature

# Current features
* Markdown Editor for changelogs, issues and answer to issues
* Discord webhook to notify when an issue is answered, a changelog is posted or an issue is created, closed / re-opened;
* Automaticlly close an issue when posting a changelog that solve the issue
* Automaticcly add a message to an issue when it has been closed / re-opened
* Add tag to issues depending on your role 

# Incoming features
* [ ] Discord webhook may mention discord users that did contribute to an issue using the [discord-auth](https://market.azuriom.com/resources/62?locale=en) plugin
* [ ] Improve UI, mostly text editor, to add ability to mention an issue or a changelog 
* [ ] Add automatic messages when a tag is added 
* [x] Add automatic messages when title changes
* [ ] Add filters for issues (closed/open/tag/user/search)
* [x] Add ability to upload images from editor
