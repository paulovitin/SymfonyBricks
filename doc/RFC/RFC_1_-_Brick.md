# RFC 1 - Brick

This RFC is about how **users** create/manage **bricks**.

To comprehend better the keywords used in this document, [ read the dictionary.](https://github.com/inmarelibero/SymfonyBricks/blob/master/doc/dictionary.md)

## Purpose
User A must be able to sumbit to SymfonyBricks as many **bricks** as he wants.

It is desiderable to use a versioning system (eg. git) in some way, to let user B see the source code, contribute, review, propose modifications, etc.. of user A.

## Proposal

When a user submits a **brick**, it happens the follow:

- user specifies a *title* of the **brick** (eg: *"How to integrate Botostrap in Symfony2"*)
- user adds the *link* to a file hosted on a github repository (normally owned by him) representing the *body* of the **brick** (the actual guide)
- user specifies the *language* of the **brick**
- user (optionally) adds some *tags*

Then he can decide to publish or not the **brick**.

In this way SymfonyBricks does not manage directly the process of editing/creation of a **brick**, but delegates it to github. User can then work on his repo being familiar with github, and then its files will be available on SymfonyBricks.

A use case could be the following:

- user A creates the github repo **http://github.com/userA/SymfonyBricks-Share** (example name), and pushes the file **http://github.com/userA/SymfonyBricks-Share/blob/master/How-to-integrate-bootstrap-in-Symfony2.en.md**
- user A creates the **brick** *"How to integrate Botostrap in Symfony2"* and adds the link **http://github.com/userA/SymfonyBricks-Share/blob/master/How-to-integrate-bootstrap-in-Symfony2.en.md** to it (as described above)
- when user A saves the **bricks**:
    - a record of the table *brick* is created with the following fields:
        - **title**: *"How to integrate Botostrap in Symfony2"*
        - **url**: *"http://github.com/userA/SymfonyBricks-Share/blob/master/How-to-integrate-bootstrap-in-Symfony2.en.md"*
        - **content**: content of the file *http://github.com/userA/SymfonyBricks-Share/blob/master/How-to-integrate-bootstrap-in-Symfony2.en.md* fetched by curl and copied in the database. Content is copied from github to database but not directly editable; this is done to build a smart search system later.
        - **commit_checksum**: the checksum of the file *http://github.com/userA/SymfonyBricks-Share/blob/master/How-to-integrate-bootstrap-in-Symfony2.en.md* to later verify if there are updates
        - **tags**: n:m relation with *tag* table
       
       
### Translations

**Bricks** submitted in different languages are managed through [Translatable Doctrine Extension](https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md)

### Versioning

Using github should be not necessary; the *link* attribute should point to a generic resource available on internet, containing a file in [Markdown format](http://daringfireball.net/projects/markdown/syntax).


### Brick Syntax

To guarantee an high consistency among the syntax/format of the **bricks**, [Markdown format](http://daringfireball.net/projects/markdown/syntax) is chosen. This means that the *content* attribute of a **brick** is always interpreted following the rules of Markdown.

A set of syntax standards must be available for the users, to help him to create a **brick** following standard rules. This rules include the syntax to use when specifying a title, or a subtitle representing a step, or a block of code, etc...

## Discussion

[RFC 1 - Brick](https://github.com/inmarelibero/SymfonyBricks/blob/master/doc/RFC/RFC_1_-_Brick.md) can be discussed in [this issue](https://github.com/inmarelibero/SymfonyBricks/blob/master/doc/RFC/RFC_1_-_Brick.md).