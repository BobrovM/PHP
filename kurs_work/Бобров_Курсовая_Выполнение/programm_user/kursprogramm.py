import os
import json
from tkinter import ttk
from tkinter import *
import tkinter
import requests


def color_qmarks(textin):
    if isinstance(textin, str):
        search_start = 0
        qmi = -2
        qmit = -2
        put_before = "<font color = \"red\">"
        put_after = "</font>"
        while qmi != -1 and qmit != -1:
            qmi = textin.find("\"", search_start)
            if qmi != -1:
                qmit = textin.find("\"", qmi + 1)
                if qmit != -1:
                    search_start = len(textin[:qmit+1]) + len(put_before) + len(put_after)
                    textin = textin[:qmi] + put_before + textin[qmi:qmit+1] + put_after + textin[qmit+1:]
    return textin

def color_amarks(textin):
    if isinstance(textin, str):
        search_start = 0
        ami = -2
        amit = -2
        put_before = "<font color = \"red\">"
        put_after = "</font>"
        while ami != -1 and amit != -1:
            ami = textin.find("\'", search_start)
            if ami != -1:
                amit = textin.find("\'", ami + 1)
                if amit != -1:
                    search_start = len(textin[:amit+1]) + len(put_before) + len(put_after)
                    textin = textin[:ami] + put_before + textin[ami:amit+1] + put_after + textin[amit+1:]
    return textin

functions_list = ("echo", "isset", "glob", "hihglight_file", "fopen", "fread", "fclose")

def color_functions(textin):
    if isinstance(textin, str):
        for function in functions_list:
            len_func = len(function)
            last_index = 0
            index = -1
            index = textin.find(function, last_index)
            put_before = "<font color = \"green\">"
            put_after = "</font>"
            while index != -1:
                if index != 1:
                    last_index = index + len_func + len(put_before) + len(put_after)
                    textin = textin[:index] + put_before + textin[index:index+len_func] + put_after + textin[index+len_func:]
                index = textin.find(function, last_index)
    return textin

loops_and_conditions_list = ("foreach", "elseif", "if", "else", "while", "for")

def color_loopncondit(textin):
    if isinstance(textin, str):
        for lnc in loops_and_conditions_list:
            len_func = len(lnc)
            last_index = 0
            index = -1
            index = textin.find(lnc, last_index)
            put_before = "<font color = \"orange\">"
            put_after = "</font>"
            while index != -1:
                if index != 1:
                    last_index = index + len_func + len(put_before) + len(put_after)
                    textin = textin[:index] + put_before + textin[index:index+len_func] + put_after + textin[index+len_func:]
                index = textin.find(lnc, last_index)
    return textin

def process_php_text(textin):
    if isinstance(textin, str):
        textin = color_qmarks(textin)
        textin = color_amarks(textin)
        textin = color_functions(textin)
        textin = color_loopncondit(textin)
    return textin

def replace_tribrack(textin):
    if isinstance(textin, str):
        lti = -2
        rti = -2
        while lti != -1 and rti != -1:
            lti = textin.find("<", 0)
            if lti != -1:
                textin = textin[:lti] + "&lt;" + textin[lti+1:]
            rti = textin.find(">", 0)
            if rti != -1:
                textin = textin[:rti] + "&gt;" + textin[rti+1:]
    return textin

def replace_nl(textin):
    if isinstance(textin, str):
        bri = -2
        while bri != -1:
            bri = textin.find("\n")
            if bri != -1:
                textin = textin[:bri] + "<br>" + textin[bri+1:]
    return textin

ui = Tk()
ui.geometry('1440x720')
ui.title("php obserwer")
ui.resizable(False, False)
label1 = ttk.Label(text="Выбирете файл:")
selectchoice = StringVar()
choicebox = ttk.Combobox(ui, textvariable=selectchoice)
label2 = ttk.Label(text="Содержание файла:")

getfiles = requests.post("http://localhost/spoilfiles.php")
files = json.loads(getfiles.text)
fileslist = []
for string in files:
    fileslist.append(string[8:])
choicebox['values'] = fileslist
choicebox['state'] = 'readonly'

label1.pack(fill=tkinter.X, padx=5, pady=5)
label2.pack(fill=tkinter.X, padx=5, pady=5)
choicebox.pack(fill=tkinter.X, padx=5, pady=5)

def choicechanged(event):
    getfilecontents = requests.post("http://localhost/reqproc.php", {'':selectchoice.get()})
    text = getfilecontents.text
    text = replace_tribrack(text)
    phpopen = -2
    phpclose = -2
    find_start = 0
    while phpopen != -1 and phpclose != -1:
        phpopen = text.find("&lt;?php", find_start)
        if phpopen != -1:
            phpclose = text.find("?&gt;", phpopen+8)
            if phpclose != -1:
                texttophp = text[phpopen:phpclose+5]
                texttophp = process_php_text(texttophp)
                find_start = len(text[:phpopen]) + len(texttophp) + 1
                text = text[:phpopen] + texttophp + text[phpclose+5:]
    text = replace_nl(text)
    outfile = open("theoutput.html", "w")
    outfile.write(text)
    outfile.close()
    os.system("start theoutput.html")

choicebox.bind('<<ComboboxSelected>>', choicechanged)
ui.mainloop()