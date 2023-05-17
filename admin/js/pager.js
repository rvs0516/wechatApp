//歌手页不调用jquery，因此数据分页处理在这个js
/*页码*/
var Page = function(CurrentPage, RecordCount, DisplayPages, PageSize,callback, Url) {
    this.CurrentPage = parseInt(CurrentPage);
    this.RecordCount = RecordCount;
    this.First = "首页";
    this.Prev = "上页";
    this.Next = "下页";
    this.Last = "末页";
    this.DisplayPages = DisplayPages;
    this._padLeft = null;
    this._padRight = null;
    this.PageSize = PageSize;
    this.PageCount = Math.ceil(this.RecordCount / this.PageSize);
    this.Url = Url;
    this.IsShowFirstAndLast = true;
    this.IsShowPageSearch = false;

    this.GetText = function() {
        if (this.PageCount == 0 || this.CurrentPage > this.PageCount) {
            return "";
        }

        var pageStr = "<span id=\"mypage\"><span>共&nbsp;&nbsp;&nbsp;"+this.PageCount+"&nbsp;&nbsp;&nbsp;页&nbsp;&nbsp;&nbsp;"+ this.RecordCount+"条记录</span>";

        this.Padding();

        pageStr += this.RenderBeginTag();
        pageStr += this.RenderPagingContents();
        pageStr += this.RenderEndTag();
        pageStr = pageStr + "</span>";

        return pageStr;

    };

    this.RenderBeginTag = function() {
        var str = "";
        var addition = "";
        if (this.CurrentPage > 1) {
            var temp = this.CurrentPage - 1 == 1 ? "1" : (this.CurrentPage - 1);

            if (!this.Url) {
                if (this.IsShowFirstAndLast) {
                    str = "<a style=\"margin:0px 2px 0px 2px;\" id=\"page_first\" title=\"首页\" class=\"direct btnPage\" href=\"javascript:void(0)\" onclick=\""+callback+"(1," + this.PageSize + ");return false;\" >&nbsp;&nbsp;&nbsp;" + this.First + "&nbsp;&nbsp;&nbsp;</a>"
                }
            }
			str += "<span class=\"PrePageSpan\" style=\"border:0px;padding:0px;\" ><a id=\"page_pre_" + temp + "\" href=\"javascript:void(0)\" title=\"上页\" class=\"direct btnPage\" onclick=\""+callback+"(" + temp + "," + this.PageSize + ");return false;\" >" + this.Prev + "</a></span>";
        }else{
            var temp = this.CurrentPage - 1 == 1 ? "1" : (this.CurrentPage - 1);

            if (!this.Url) {
                if (this.IsShowFirstAndLast) {
                    str = "<a id=\"page_first\" title=\"首页\" class=\"direct btnPage\" href=\"javascript:void(0)\" >&nbsp;" + this.First + "</a>"
                }

            }			
			
			
		}
        return str;
    };

    this.RenderEndTag = function() {
        var str = "";
        var addition = "";
        
        if (this.CurrentPage >= 1 && this.CurrentPage != this.RecordCount) {
            var temp = this.CurrentPage + 1;
            var temp1 = Math.ceil(this.RecordCount / this.PageSize);
            if (temp > temp1) {
                str = "";
                if (this.IsShowPageSearch) {
                    str += "<span class='page_search'>Pages：" + this.CurrentPage + "/" + this.PageCount + "&nbsp;<input type='textbox' id='inputSearch' class='input'><a href='javascript:void(0)' onclick='pageGo(this)'>Go</a></span>";
                }
            }
            else {
                if (!this.Url) {

                    str = "<span class=\"NextPageSpan\" style=\"border:0px;padding:0px;\" ><a id=\"page_next_" + temp + "\" href=\"javascript:void(0)\"  title=\"下页\" class=\"direct btnPage\" onclick=\""+callback+"(" + temp + "," + this.PageSize + ");return false;\"  >" + this.Next + "</a></span>";
                    if (this.IsShowFirstAndLast) {
                        str += "<a id=\"page_last_" + temp1 + "\" title=\"尾页\" href=\"javascript:void(0)\" class=\"direct btnPage\" onclick=\""+callback+"(" + temp1 + "," + this.PageSize + ");return false;\" >" + this.Last + "</a>";
                    }

                }
            }
        }
        return str;
    };

    this.RenderPagingContents = function() {
        var str = "";
        if (this.PageCount <= this.DisplayPages) {
            str = str + this.RenderLinkRange(1, this.PageCount);
        }
        else {
            if (this.CurrentPage <= this._padRight) {
                str = str + this.RenderLinkRange(1, this.DisplayPages);
            }
            else if (CurrentPage <= (this.PageCount - this._padRight)) {
                str = str + this.RenderLinkRange((this.CurrentPage - this._padLeft), (this.CurrentPage + this._padRight));
            }
            else {
                str = str + this.RenderLinkRange((this.CurrentPage - (this.DisplayPages - ((this.PageCount - this.CurrentPage) + 1))), this.PageCount);
            }
        }

        return str;
    };

    this.RenderLinkRange = function(start, end) {
        var str = "";
        var addition = "";
        
        for (i = start; i <= end; i++) {
            if (i == this.CurrentPage) {
                str = str + "<span id=\"page_" + i + "\"  class=\"current\"><a href=\"javascript:void(0);\" ><font color='red'>" + i + "</font></span>";
            }
            else {
                var temp = (i == 1 ? 1 : i);

                if (this.Url) {
                    str = str + "<a id=\"page_" + temp + "\" href = \"" + this.Url + "_" + temp + addition + ".htm" + "\" >" + i + "</a>";
                }
                else {
                    str = str + "<a id=\"page_" + temp + "\" href=\"javascript:void(0)\" onclick=\""+callback+"(" + temp + "," + this.PageSize + ");return false;\" >" + i + "</a>";
                }

            }
        }
        return str;
    };

    this.Padding = function() {
        this._padLeft = Math.floor(this.DisplayPages / 2);
        this._padRight = this._padLeft;

        if (this.DisplayPages % 2 == 0)
            this._padLeft--;
    };
}
