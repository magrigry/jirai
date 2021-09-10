[![CodeFactor](https://www.codefactor.io/repository/github/magrigry/jirai/badge)](https://www.codefactor.io/repository/github/magrigry/jirai)

# jirai
An azuriom plugin to manage issues and changelogs

# Demo 
https://www.over2craft.com/jirai

# Disclaimer
This is a very very first release of a plugin that aim to give ability 
to server administrators to manage their bugs, suggestions and changelogs in a better way

I would appreciate pull request either to improve code quality or improve and add some feature

# Current features
* You or your users can create some issues from a dashboard using a markdown editor. An issue can be either a suggestion, or a bug. 
* Chose which role can post, edit, delete, or edit others issues using Azuriom permission system
* Chose which role can answer to issues
* Post changelog using a markdown editor.
* Changelogs can be linked to some opened issues that will be automatically close when the changelog is posted.
* Upload screenshot directly within the markdown editor. 
* Configure Discord webhook to notify answered issues, posted changelog, closed issues, or opened issues.
* Automatically add a message to an issue when status change to keep track of changes
* Define some tags that can be assigned to issues
* Restrict which role can use defined tags

# Installation
* Download the plugin from your panel
* Define permission. By default, nobody can post issues
* Define tags in plugin's configuration if you need them

# Incoming features
* [x] Discord webhook may mention discord users that did contribute to an issue using the [discord-auth](https://market.azuriom.com/resources/62?locale=en) plugin
* [x] Add ability to upload images from editor
* [x] Add automatic messages when title changes
* [x] display tags in issues title
* [ ] Improve UI, mostly text editor, to add ability to mention an issue or a changelog 
* [ ] Add filters for issues (closed/open/tag/user/search)
* [ ] Make an issue privatable so tags role restriction also apply to visibility
* [ ] French translation *(any Pull Request on github would be appreciated)*
* [ ] Add discord bot feature to take advantage of discord threads and replace webhooks
