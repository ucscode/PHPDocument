<?php

namespace Ucscode\UssElement\Enums;

/**
 * An enum of all recognized HTML tag names
 *
 * @author Uchenna Ajah <uche23mail@gmail.com>
 */
enum NodeNameEnum: string
{
    case NODE_A = 'A';
    case NODE_ABBR = 'ABBR';
    case NODE_ACRONYM = 'ACRONYM';
    case NODE_ADDRESS = 'ADDRESS';
    case NODE_APPLET = 'APPLET';
    case NODE_AREA = 'AREA';
    case NODE_ARTICLE = 'ARTICLE';
    case NODE_ASIDE = 'ASIDE';
    case NODE_AUDIO = 'AUDIO';
    case NODE_B = 'B';
    case NODE_BASE = 'BASE';
    case NODE_BASEFONT = 'BASEFONT';
    case NODE_BDI = 'BDI';
    case NODE_BDO = 'BDO';
    case NODE_BGSOUND = 'BGSOUND';
    case NODE_BIG = 'BIG';
    case NODE_BLINK = 'BLINK';
    case NODE_BLOCKQUOTE = 'BLOCKQUOTE';
    case NODE_BODY = 'BODY';
    case NODE_BR = 'BR';
    case NODE_BUTTON = 'BUTTON';
    case NODE_CANVAS = 'CANVAS';
    case NODE_CAPTION = 'CAPTION';
    case NODE_CENTER = 'CENTER';
    case NODE_CITE = 'CITE';
    case NODE_CODE = 'CODE';
    case NODE_COL = 'COL';
    case NODE_COLGROUP = 'COLGROUP';
    case NODE_COMMAND = 'COMMAND';
    case NODE_CONTENT = 'CONTENT';
    case NODE_DATA = 'DATA';
    case NODE_DATALIST = 'DATALIST';
    case NODE_DD = 'DD';
    case NODE_DEL = 'DEL';
    case NODE_DETAILS = 'DETAILS';
    case NODE_DFN = 'DFN';
    case NODE_DIALOG = 'DIALOG';
    case NODE_DIR = 'DIR';
    case NODE_DIV = 'DIV';
    case NODE_DL = 'DL';
    case NODE_DT = 'DT';
    case NODE_ELEMENT = 'ELEMENT';
    case NODE_EM = 'EM';
    case NODE_EMBED = 'EMBED';
    case NODE_FIELDSET = 'FIELDSET';
    case NODE_FIGCAPTION = 'FIGCAPTION';
    case NODE_FIGURE = 'FIGURE';
    case NODE_FONT = 'FONT';
    case NODE_FOOTER = 'FOOTER';
    case NODE_FORM = 'FORM';
    case NODE_FRAME = 'FRAME';
    case NODE_FRAMESET = 'FRAMESET';
    case NODE_H1 = 'H1';
    case NODE_H2 = 'H2';
    case NODE_H3 = 'H3';
    case NODE_H4 = 'H4';
    case NODE_H5 = 'H5';
    case NODE_H6 = 'H6';
    case NODE_HEAD = 'HEAD';
    case NODE_HEADER = 'HEADER';
    case NODE_HGROUP = 'HGROUP';
    case NODE_HR = 'HR';
    case NODE_HTML = 'HTML';
    case NODE_I = 'I';
    case NODE_IFRAME = 'IFRAME';
    case NODE_IMAGE = 'IMAGE';
    case NODE_IMG = 'IMG';
    case NODE_INPUT = 'INPUT';
    case NODE_INS = 'INS';
    case NODE_ISINDEX = 'ISINDEX';
    case NODE_KBD = 'KBD';
    case NODE_KEYGEN = 'KEYGEN';
    case NODE_LABEL = 'LABEL';
    case NODE_LEGEND = 'LEGEND';
    case NODE_LI = 'LI';
    case NODE_LINK = 'LINK';
    case NODE_LISTING = 'LISTING';
    case NODE_MAIN = 'MAIN';
    case NODE_MAP = 'MAP';
    case NODE_MARK = 'MARK';
    case NODE_MARQUEE = 'MARQUEE';
    case NODE_MENU = 'MENU';
    case NODE_MENUITEM = 'MENUITEM';
    case NODE_META = 'META';
    case NODE_METER = 'METER';
    case NODE_NAV = 'NAV';
    case NODE_NOBR = 'NOBR';
    case NODE_NOEMBED = 'NOEMBED';
    case NODE_NOSCRIPT = 'NOSCRIPT';
    case NODE_OBJECT = 'OBJECT';
    case NODE_OL = 'OL';
    case NODE_OPTGROUP = 'OPTGROUP';
    case NODE_OPTION = 'OPTION';
    case NODE_OUTPUT = 'OUTPUT';
    case NODE_P = 'P';
    case NODE_PARAM = 'PARAM';
    case NODE_PLAINTEXT = 'PLAINTEXT';
    case NODE_PRE = 'PRE';
    case NODE_PROGRESS = 'PROGRESS';
    case NODE_Q = 'Q';
    case NODE_RB = 'RB';
    case NODE_RP = 'RP';
    case NODE_RT = 'RT';
    case NODE_RTC = 'RTC';
    case NODE_RUBY = 'RUBY';
    case NODE_S = 'S';
    case NODE_SAMP = 'SAMP';
    case NODE_SCRIPT = 'SCRIPT';
    case NODE_SECTION = 'SECTION';
    case NODE_SELECT = 'SELECT';
    case NODE_SMALL = 'SMALL';
    case NODE_SOURCE = 'SOURCE';
    case NODE_SPAN = 'SPAN';
    case NODE_STRIKE = 'STRIKE';
    case NODE_STRONG = 'STRONG';
    case NODE_STYLE = 'STYLE';
    case NODE_SUB = 'SUB';
    case NODE_SUMMARY = 'SUMMARY';
    case NODE_SUP = 'SUP';
    case NODE_SVG = 'SVG';
    case NODE_TABLE = 'TABLE';
    case NODE_TBODY = 'TBODY';
    case NODE_TD = 'TD';
    case NODE_TEMPLATE = 'TEMPLATE';
    case NODE_TEXTAREA = 'TEXTAREA';
    case NODE_TFOOT = 'TFOOT';
    case NODE_TH = 'TH';
    case NODE_THEAD = 'THEAD';
    case NODE_TIME = 'TIME';
    case NODE_TITLE = 'TITLE';
    case NODE_TR = 'TR';
    case NODE_TRACK = 'TRACK';
    case NODE_TT = 'TT';
    case NODE_U = 'U';
    case NODE_UL = 'UL';
    case NODE_VAR = 'VAR';
    case NODE_VIDEO = 'VIDEO';
    case NODE_WBR = 'WBR';
    case NODE_XMP = 'XMP';

    /**
     * Return all enums whose type are by default "void"
     *
     * The returned enums are those that do not have closing tags
     *
     * @return array<NodeNameEnum>
     */
    public static function voidCases(): array
    {
        return array_filter(self::cases(), function ($value) {
            return in_array($value, [
                self::NODE_AREA,
                self::NODE_BASE,
                self::NODE_BR,
                self::NODE_COL,
                self::NODE_EMBED,
                self::NODE_HR,
                self::NODE_IMG,
                self::NODE_INPUT,
                self::NODE_LINK,
                self::NODE_META,
                self::NODE_PARAM,
                self::NODE_SOURCE,
                self::NODE_TRACK,
                self::NODE_WBR,
            ]);
        });
    }
}
